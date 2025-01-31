<?php

declare(strict_types=1);

namespace Tests\Unit\Resource;

use Apiera\Sdk\Client;
use Apiera\Sdk\DataMapper\AttributeTermDataMapper;
use Apiera\Sdk\DTO\QueryParameters;
use Apiera\Sdk\DTO\Request\AttributeTerm\AttributeTermRequest;
use Apiera\Sdk\DTO\Response\AttributeTerm\AttributeTermCollectionResponse;
use Apiera\Sdk\DTO\Response\AttributeTerm\AttributeTermResponse;
use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Exception\InvalidRequestException;
use Apiera\Sdk\Resource\AttributeTermResource;
use DateTimeImmutable;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Uid\Uuid;

final class AttributeTermResourceTest extends TestCase
{
    private Client|MockObject $clientMock;
    private AttributeTermDataMapper|MockObject $mapperMock;
    private AttributeTermResource $resource;
    private AttributeTermRequest $request;
    private AttributeTermResponse $response;
    private AttributeTermCollectionResponse $collectionResponse;

    /** @var array<string, mixed> */
    private array $mockResponseData;

    /** @var array<string, mixed> */
    private array $mockCollectionData;

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     */
    public function testFindRequiresAttributeIri(): void
    {
        $this->expectException(InvalidRequestException::class);
        $this->resource->find(new AttributeTermRequest(name: 'Example term'));
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testFindReturnsCollection(): void
    {
        $responseMock = $this->createMock(ResponseInterface::class);

        $this->clientMock->expects($this->once())
            ->method('get')
            ->with('/api/v1/stores/321/attributes/123/terms')
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
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testFindOneByReturnsFirstResult(): void
    {
        $params = new QueryParameters(filters: ['name' => 'Example term']);

        $this->clientMock->expects($this->once())
            ->method('get')
            ->with('/api/v1/stores/321/attributes/123/terms')
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
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testFindOneByThrowsExceptionWhenEmpty(): void
    {
        $emptyCollectionData = [
            '@context' => '/api/contexts/AttributeTerm',
            '@id' => '/api/v1/stores/321/attributes/123/terms',
            '@type' => 'Collection',
            'member' => [],
            'totalItems' => 0,
        ];

        $emptyCollection = new AttributeTermCollectionResponse(
            context: '/api/contexts/AttributeTerm',
            id: '/api/v1/stores/321/attributes/123/terms',
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
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     */
    public function testGetRequiresIri(): void
    {
        $this->expectException(InvalidRequestException::class);
        $this->resource->get(new AttributeTermRequest(name: 'Example term'));
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testGetReturnsAttributeTerm(): void
    {
        $request = new AttributeTermRequest(
            name: 'Example term',
            iri: '/api/v1/stores/321/attributes/123/terms/456'
        );

        $responseMock = $this->createMock(ResponseInterface::class);

        $this->clientMock->expects($this->once())
            ->method('get')
            ->with('/api/v1/stores/321/attributes/123/terms/456')
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
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     */
    public function testCreateRequiresAttributeIri(): void
    {
        $this->expectException(InvalidRequestException::class);
        $this->resource->create(new AttributeTermRequest(name: 'Example term'));
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testCreateAttributeTerm(): void
    {
        $requestData = [
            'name' => 'Example term',
        ];

        $responseMock = $this->createMock(ResponseInterface::class);

        $this->mapperMock->expects($this->once())
            ->method('toRequestData')
            ->with($this->request)
            ->willReturn($requestData);

        $this->clientMock->expects($this->once())
            ->method('post')
            ->with('/api/v1/stores/321/attributes/123/terms', $requestData)
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
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     */
    public function testUpdateRequiresIri(): void
    {
        $this->expectException(InvalidRequestException::class);
        $this->resource->update(new AttributeTermRequest(name: 'Example term'));
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testUpdateAttributeTerm(): void
    {
        $request = new AttributeTermRequest(
            name: 'Updated term',
            iri: '/api/v1/stores/321/attributes/123/terms/456'
        );

        $requestData = ['name' => 'Updated term'];
        $updatedResponseData = array_merge($this->mockResponseData, ['name' => 'Updated term']);
        $responseMock = $this->createMock(ResponseInterface::class);

        $this->mapperMock->expects($this->once())
            ->method('toRequestData')
            ->with($request)
            ->willReturn($requestData);

        $this->clientMock->expects($this->once())
            ->method('patch')
            ->with('/api/v1/stores/321/attributes/123/terms/456', $requestData)
            ->willReturn($responseMock);

        $this->clientMock->expects($this->once())
            ->method('decodeResponse')
            ->with($responseMock)
            ->willReturn($updatedResponseData);

        $updatedResponse = new AttributeTermResponse(
            id: '/api/v1/stores/321/attributes/123/terms/456',
            type: LdType::AttributeTerm,
            uuid: Uuid::fromString('f47ac10b-58cc-4372-a567-0e02b2c3d479'),
            createdAt: new DateTimeImmutable('2024-01-25T12:00:00+00:00'),
            updatedAt: new DateTimeImmutable('2024-01-25T12:00:00+00:00'),
            name: 'Updated term',
            attribute: '/api/v1/stores/321/attributes/123',
            store: '/api/v1/stores/321'
        );

        $this->mapperMock->expects($this->once())
            ->method('fromResponse')
            ->with($updatedResponseData)
            ->willReturn($updatedResponse);

        $result = $this->resource->update($request);
        $this->assertSame($updatedResponse, $result);
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     */
    public function testDeleteRequiresIri(): void
    {
        $this->expectException(InvalidRequestException::class);
        $this->resource->delete(new AttributeTermRequest(name: 'Example term'));
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testDeleteAttributeTerm(): void
    {
        $request = new AttributeTermRequest(
            name: 'Example term',
            iri: '/api/v1/stores/321/attributes/123/terms/456'
        );

        $responseMock = $this->createMock(ResponseInterface::class);

        $this->clientMock->expects($this->once())
            ->method('delete')
            ->with('/api/v1/stores/321/attributes/123/terms/456')
            ->willReturn($responseMock);

        $this->resource->delete($request);
    }

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    protected function setUp(): void
    {
        $this->clientMock = $this->createMock(Client::class);
        $this->mapperMock = $this->createMock(AttributeTermDataMapper::class);
        $this->resource = new AttributeTermResource($this->clientMock, $this->mapperMock);

        // Setup realistic test data
        $this->request = new AttributeTermRequest(
            name: 'Example term',
            attribute: '/api/v1/stores/321/attributes/123'
        );

        $uuid = Uuid::fromString('f47ac10b-58cc-4372-a567-0e02b2c3d479');
        $createdAt = new DateTimeImmutable('2024-01-25T12:00:00+00:00');
        $updatedAt = new DateTimeImmutable('2024-01-25T12:00:00+00:00');

        $this->response = new AttributeTermResponse(
            id: '/api/v1/stores/321/attributes/123/terms/456',
            type: LdType::AttributeTerm,
            uuid: $uuid,
            createdAt: $createdAt,
            updatedAt: $updatedAt,
            name: 'Example term',
            attribute: '/api/v1/stores/321/attributes/123',
            store: '/api/v1/stores/321'
        );

        $this->mockResponseData = [
            '@id' => '/api/v1/stores/321/attributes/123/terms/456',
            '@type' => 'AttributeTerm',
            'uuid' => 'f47ac10b-58cc-4372-a567-0e02b2c3d479',
            'name' => 'Example term',
            'attribute' => '/api/v1/stores/321/attributes/123',
            'store' => '/api/v1/stores/321',
            'createdAt' => '2024-01-25T12:00:00+00:00',
            'updatedAt' => '2024-01-25T12:00:00+00:00',
        ];

        $this->mockCollectionData = [
            '@context' => '/api/contexts/AttributeTerm',
            '@id' => '/api/v1/stores/321/attributes/123/terms',
            '@type' => 'Collection',
            'member' => [$this->mockResponseData],
            'totalItems' => 1,
            'view' => '/api/v1/attributes/123/terms?page=1',
            'firstPage' => '/api/v1/attributes/123/terms?page=1',
            'lastPage' => '/api/v1/attributes/123/terms?page=1',
        ];

        $this->collectionResponse = new AttributeTermCollectionResponse(
            context: '/api/contexts/AttributeTerm',
            id: '/api/v1/stores/321/attributes/123/terms',
            type: LdType::Collection,
            members: [$this->response],
            totalItems: 1,
            view: '/api/v1/attributes/123/terms?page=1',
            firstPage: '/api/v1/attributes/123/terms?page=1',
            lastPage: '/api/v1/attributes/123/terms?page=1',
        );
    }
}
