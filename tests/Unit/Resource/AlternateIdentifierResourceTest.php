<?php

declare(strict_types=1);

namespace Tests\Unit\Resource;

use Apiera\Sdk\Client;
use Apiera\Sdk\DataMapper\ReflectionAttributeDataMapper;
use Apiera\Sdk\DTO\QueryParameters;
use Apiera\Sdk\DTO\Request\AlternateIdentifier\AlternateIdentifierRequest;
use Apiera\Sdk\DTO\Response\AlternateIdentifier\AlternateIdentifierCollectionResponse;
use Apiera\Sdk\DTO\Response\AlternateIdentifier\AlternateIdentifierResponse;
use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Exception\InvalidRequestException;
use Apiera\Sdk\Resource\AlternateIdentifierResource;
use DateTimeImmutable;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Uid\Uuid;

final class AlternateIdentifierResourceTest extends TestCase
{
    private MockObject $clientMock;
    private MockObject $mapperMock;
    private AlternateIdentifierResource $resource;
    private AlternateIdentifierRequest $request;
    private AlternateIdentifierResponse $response;
    private AlternateIdentifierCollectionResponse $collectionResponse;

    /** @var array<string, mixed> */
    private array $mockResponseData;

    /** @var array<string, mixed> */
    private array $mockCollectionData;

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testFindReturnsCollection(): void
    {
        $responseMock = $this->createMock(ResponseInterface::class);

        $this->clientMock->expects($this->once())
            ->method('get')
            ->with('/api/v1/alternate_identifiers')
            ->willReturn($responseMock);

        $this->clientMock->expects($this->once())
            ->method('decodeResponse')
            ->with($responseMock)
            ->willReturn($this->mockCollectionData);

        $this->mapperMock->expects($this->once())
            ->method('fromCollectionResponse')
            ->with($this->mockCollectionData)
            ->willReturn($this->collectionResponse);

        $result = $this->resource->find($this->request);
        $this->assertSame($this->collectionResponse, $result);
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testFindOneByReturnsFirstResult(): void
    {
        $params = new QueryParameters(filters: ['code' => 'ABC123']);

        $this->clientMock->expects($this->once())
            ->method('get')
            ->with('/api/v1/alternate_identifiers')
            ->willReturn($this->createMock(ResponseInterface::class));

        $this->clientMock->expects($this->once())
            ->method('decodeResponse')
            ->willReturn($this->mockCollectionData);

        $this->mapperMock->expects($this->once())
            ->method('fromCollectionResponse')
            ->willReturn($this->collectionResponse);

        $result = $this->resource->findOneBy($this->request, $params);
        $this->assertSame($this->response, $result);
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testFindOneByThrowsExceptionWhenEmpty(): void
    {
        $emptyCollectionData = [
            '@context' => '/api/v1/contexts/AlternateIdentifier',
            '@id' => '/api/alternate_identifiers',
            '@type' => 'Collection',
            'member' => [],
            'totalItems' => 0,
        ];

        $emptyCollection = new AlternateIdentifierCollectionResponse(
            ldContext: '/api/contexts/AlternateIdentifier',
            ldId: '/api/alternate_identifiers',
            ldType: LdType::Collection,
            ldMembers: [],
            ldTotalItems: 0
        );

        $this->clientMock->method('get')
            ->willReturn($this->createMock(ResponseInterface::class));

        $this->clientMock->method('decodeResponse')
            ->willReturn($emptyCollectionData);

        $this->mapperMock->method('fromCollectionResponse')
            ->willReturn($emptyCollection);

        $this->expectException(InvalidRequestException::class);
        $this->resource->findOneBy($this->request, new QueryParameters());
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function testGetRequiresIri(): void
    {
        $this->expectException(InvalidRequestException::class);
        $this->resource->get(new AlternateIdentifierRequest(code: 'ABC123', type: 'gtin'));
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testGetReturnsAlternateIdentifier(): void
    {
        $request = new AlternateIdentifierRequest(
            code: 'ABC123',
            type: 'gtin',
            iri: '/api/v1/alternate_identifiers/456'
        );

        $responseMock = $this->createMock(ResponseInterface::class);

        $this->clientMock->expects($this->once())
            ->method('get')
            ->with('/api/v1/alternate_identifiers/456')
            ->willReturn($responseMock);

        $this->clientMock->expects($this->once())
            ->method('decodeResponse')
            ->with($responseMock)
            ->willReturn($this->mockResponseData);

        $this->mapperMock->expects($this->once())
            ->method('fromResponse')
            ->with($this->mockResponseData)
            ->willReturn($this->response);

        $result = $this->resource->get($request);
        $this->assertSame($this->response, $result);
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testCreateAlternateIdentifier(): void
    {
        $requestData = [
            'code' => 'ABC123',
            'type' => 'gtin',
        ];

        $responseMock = $this->createMock(ResponseInterface::class);

        $this->mapperMock->expects($this->once())
            ->method('toRequestData')
            ->with($this->request)
            ->willReturn($requestData);

        $this->clientMock->expects($this->once())
            ->method('post')
            ->with('/api/v1/alternate_identifiers', $requestData)
            ->willReturn($responseMock);

        $this->clientMock->expects($this->once())
            ->method('decodeResponse')
            ->with($responseMock)
            ->willReturn($this->mockResponseData);

        $this->mapperMock->expects($this->once())
            ->method('fromResponse')
            ->with($this->mockResponseData)
            ->willReturn($this->response);

        $result = $this->resource->create($this->request);
        $this->assertSame($this->response, $result);
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function testUpdateRequiresIri(): void
    {
        $this->expectException(InvalidRequestException::class);
        $this->resource->update(new AlternateIdentifierRequest(code: 'ABC123', type: 'gtin'));
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testUpdateAlternateIdentifier(): void
    {
        $request = new AlternateIdentifierRequest(
            code: 'XYZ789',
            type: 'ean',
            iri: '/api/v1/alternate_identifiers/456'
        );

        $requestData = ['code' => 'XYZ789', 'type' => 'ean'];
        $updatedResponseData = array_merge($this->mockResponseData, ['code' => 'XYZ789', 'type' => 'ean']);
        $responseMock = $this->createMock(ResponseInterface::class);

        $this->mapperMock->expects($this->once())
            ->method('toRequestData')
            ->with($request)
            ->willReturn($requestData);

        $this->clientMock->expects($this->once())
            ->method('patch')
            ->with('/api/v1/alternate_identifiers/456', $requestData)
            ->willReturn($responseMock);

        $this->clientMock->expects($this->once())
            ->method('decodeResponse')
            ->with($responseMock)
            ->willReturn($updatedResponseData);

        $updatedResponse = new AlternateIdentifierResponse(
            ldId: '/api/v1/alternate_identifiers/456',
            ldType: LdType::AlternateIdentifier,
            uuid: Uuid::fromString('f47ac10b-58cc-4372-a567-0e02b2c3d479'),
            createdAt: new DateTimeImmutable('2024-01-25T12:00:00+00:00'),
            updatedAt: new DateTimeImmutable('2024-01-25T12:00:00+00:00'),
            type: 'ean',
            code: 'XYZ789'
        );

        $this->mapperMock->expects($this->once())
            ->method('fromResponse')
            ->with($updatedResponseData)
            ->willReturn($updatedResponse);

        $result = $this->resource->update($request);
        $this->assertSame($updatedResponse, $result);
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     */
    public function testDeleteRequiresIri(): void
    {
        $this->expectException(InvalidRequestException::class);
        $this->resource->delete(new AlternateIdentifierRequest(code: 'ABC123', type: 'gtin'));
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testDeleteAlternateIdentifier(): void
    {
        $request = new AlternateIdentifierRequest(
            code: 'ABC123',
            type: 'gtin',
            iri: '/api/v1/alternate_identifiers/456'
        );

        $responseMock = $this->createMock(ResponseInterface::class);

        $this->clientMock->expects($this->once())
            ->method('delete')
            ->with('/api/v1/alternate_identifiers/456')
            ->willReturn($responseMock);

        $this->resource->delete($request);
    }

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    protected function setUp(): void
    {
        /** @phpstan-ignore-next-line */
        $this->clientMock = $this->createMock(Client::class);
        /** @phpstan-ignore-next-line */
        $this->mapperMock = $this->createMock(ReflectionAttributeDataMapper::class);
        $this->resource = new AlternateIdentifierResource($this->clientMock, $this->mapperMock);

        // Setup realistic test data
        $this->request = new AlternateIdentifierRequest(
            code: 'ABC123',
            type: 'gtin'
        );

        $uuid = Uuid::fromString('f47ac10b-58cc-4372-a567-0e02b2c3d479');
        $createdAt = new DateTimeImmutable('2024-01-25T12:00:00+00:00');
        $updatedAt = new DateTimeImmutable('2024-01-25T12:00:00+00:00');

        $this->response = new AlternateIdentifierResponse(
            ldId: '/api/alternate_identifiers/456',
            ldType: LdType::AlternateIdentifier,
            uuid: $uuid,
            createdAt: $createdAt,
            updatedAt: $updatedAt,
            type: 'gtin',
            code: 'ABC123'
        );

        $this->mockResponseData = [
            '@id' => '/api/alternate_identifiers/456',
            '@type' => 'AlternateIdentifier',
            'uuid' => 'f47ac10b-58cc-4372-a567-0e02b2c3d479',
            'type' => 'gtin',
            'code' => 'ABC123',
            'createdAt' => '2024-01-25T12:00:00+00:00',
            'updatedAt' => '2024-01-25T12:00:00+00:00',
        ];

        $this->mockCollectionData = [
            '@context' => '/api/contexts/AlternateIdentifier',
            '@id' => '/api/alternate_identifiers',
            '@type' => 'Collection',
            'member' => [$this->mockResponseData],
            'totalItems' => 1,
            'view' => '/api/alternate_identifiers?page=1',
            'firstPage' => '/api/alternate_identifiers?page=1',
            'lastPage' => '/api/alternate_identifiers?page=1',
        ];

        $this->collectionResponse = new AlternateIdentifierCollectionResponse(
            ldContext: '/api/contexts/AlternateIdentifier',
            ldId: '/api/alternate_identifiers',
            ldType: LdType::Collection,
            ldMembers: [$this->response],
            ldTotalItems: 1,
            ldView: '/api/alternate_identifiers?page=1',
            firstPage: '/api/alternate_identifiers?page=1',
            lastPage: '/api/alternate_identifiers?page=1'
        );
    }
}
