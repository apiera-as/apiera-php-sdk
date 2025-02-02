<?php

declare(strict_types=1);

namespace Tests\Unit\Resource;

use Apiera\Sdk\Client;
use Apiera\Sdk\DataMapper\ReflectionAttributeDataMapper;
use Apiera\Sdk\DTO\QueryParameters;
use Apiera\Sdk\DTO\Request\Property\PropertyRequest;
use Apiera\Sdk\DTO\Response\Property\PropertyCollectionResponse;
use Apiera\Sdk\DTO\Response\Property\PropertyResponse;
use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Exception\InvalidRequestException;
use Apiera\Sdk\Resource\PropertyResource;
use DateTimeImmutable;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Uid\Uuid;

final class PropertyResourceTest extends TestCase
{
    private MockObject $clientMock;
    private MockObject $mapperMock;
    private PropertyResource $resource;
    private PropertyRequest $request;
    private PropertyResponse $response;
    private PropertyCollectionResponse $collectionResponse;

    /** @var array<string, mixed> */
    private array $mockResponseData;

    /** @var array<string, mixed> */
    private array $mockCollectionData;

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function testFindRequiresStoreIri(): void
    {
        $this->expectException(InvalidRequestException::class);
        $this->resource->find(new PropertyRequest(name: 'Test property'));
    }

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
            ->with('/api/v1/stores/123/properties')
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
        $params = new QueryParameters(filters: ['name' => 'Test property']);

        $this->clientMock->expects($this->once())
            ->method('get')
            ->with('/api/v1/stores/123/properties')
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
            '@context' => '/api/v1/contexts/Property',
            '@id' => '/api/v1/properties',
            '@type' => 'Collection',
            'member' => [],
            'totalItems' => 0,
        ];

        $emptyCollection = new PropertyCollectionResponse(
            context: '/api/v1/contexts/Property',
            id: '/api/v1/properties',
            type: LdType::Collection,
            members: [],
            totalItems: 0
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
        $this->resource->get(new PropertyRequest(name: 'Test property'));
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testGetReturnsProperty(): void
    {
        $request = new PropertyRequest(
            name: 'Test property',
            iri: '/api/v1/stores/123/properties/456'
        );

        $responseMock = $this->createMock(ResponseInterface::class);

        $this->clientMock->expects($this->once())
            ->method('get')
            ->with('/api/v1/stores/123/properties/456')
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
     */
    public function testCreateRequiresStoreIri(): void
    {
        $this->expectException(InvalidRequestException::class);
        $this->resource->create(new PropertyRequest(name: 'Test property'));
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testCreateProperty(): void
    {
        $requestData = [
            'name' => 'Test property',
        ];

        $responseMock = $this->createMock(ResponseInterface::class);

        $this->mapperMock->expects($this->once())
            ->method('toRequestData')
            ->with($this->request)
            ->willReturn($requestData);

        $this->clientMock->expects($this->once())
            ->method('post')
            ->with('/api/v1/stores/123/properties', $requestData)
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
        $this->resource->update(new PropertyRequest(name: 'Test property'));
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testUpdateProperty(): void
    {
        $request = new PropertyRequest(
            name: 'Updated property',
            iri: '/api/v1/stores/123/properties/456'
        );

        $requestData = ['name' => 'Updated property'];
        $updatedResponseData = array_merge($this->mockResponseData, ['name' => 'Updated property']);
        $responseMock = $this->createMock(ResponseInterface::class);

        $this->mapperMock->expects($this->once())
            ->method('toRequestData')
            ->with($request)
            ->willReturn($requestData);

        $this->clientMock->expects($this->once())
            ->method('patch')
            ->with('/api/v1/stores/123/properties/456', $requestData)
            ->willReturn($responseMock);

        $this->clientMock->expects($this->once())
            ->method('decodeResponse')
            ->with($responseMock)
            ->willReturn($updatedResponseData);

        $updatedResponse = new PropertyResponse(
            ldId: '/api/v1/stores/123/properties/456',
            ldType: LdType::Property,
            uuid: Uuid::fromString('f47ac10b-58cc-4372-a567-0e02b2c3d479'),
            createdAt: new DateTimeImmutable('2024-01-25T12:00:00+00:00'),
            updatedAt: new DateTimeImmutable('2024-01-25T12:00:00+00:00'),
            name: 'Updated property',
            store: '/api/v1/stores/123'
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
        $this->resource->delete(new PropertyRequest(name: 'Test property'));
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testDeleteProperty(): void
    {
        $request = new PropertyRequest(
            name: 'Test property',
            iri: '/api/v1/stores/123/properties/456'
        );

        $responseMock = $this->createMock(ResponseInterface::class);

        $this->clientMock->expects($this->once())
            ->method('delete')
            ->with('/api/v1/stores/123/properties/456')
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
        $this->resource = new PropertyResource($this->clientMock, $this->mapperMock);

        // Setup realistic test data
        $this->request = new PropertyRequest(
            name: 'Test property',
            store: '/api/v1/stores/123'
        );

        $uuid = Uuid::fromString('f47ac10b-58cc-4372-a567-0e02b2c3d479');
        $createdAt = new DateTimeImmutable('2024-01-25T12:00:00+00:00');
        $updatedAt = new DateTimeImmutable('2024-01-25T12:00:00+00:00');

        $this->response = new PropertyResponse(
            ldId: '/api/v1/stores/123/properties/456',
            ldType: LdType::Property,
            uuid: $uuid,
            createdAt: $createdAt,
            updatedAt: $updatedAt,
            name: 'Test property',
            store: '/api/v1/stores/123'
        );

        $this->mockResponseData = [
            '@id' => '/api/v1/stores/123/properties/456',
            '@type' => 'Property',
            'uuid' => 'f47ac10b-58cc-4372-a567-0e02b2c3d479',
            'name' => 'Test property',
            'store' => '/api/v1/stores/123',
            'createdAt' => '2024-01-25T12:00:00+00:00',
            'updatedAt' => '2024-01-25T12:00:00+00:00',
        ];

        $this->mockCollectionData = [
            '@context' => '/api/v1/contexts/Property',
            '@id' => '/api/v1/stores/123/properties',
            '@type' => 'Collection',
            'member' => [$this->mockResponseData],
            'totalItems' => 1,
            'view' => '/api/v1/stores/123/properties?page=1',
            'firstPage' => '/api/v1/stores/123/properties?page=1',
            'lastPage' => '/api/v1/stores/123/properties?page=1',
        ];

        $this->collectionResponse = new PropertyCollectionResponse(
            context: '/api/v1/contexts/Property',
            id: '/api/v1/stores/123/properties',
            type: LdType::Collection,
            members: [$this->response],
            totalItems: 1,
            view: '/api/v1/stores/123/properties?page=1',
            firstPage: '/api/v1/stores/123/properties?page=1',
            lastPage: '/api/v1/stores/123/properties?page=1'
        );
    }
}
