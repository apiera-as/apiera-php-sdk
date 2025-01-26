<?php

declare(strict_types=1);

namespace Tests\Unit\Resource;

use Apiera\Sdk\Client;
use Apiera\Sdk\DataMapper\AttributeDataMapper;
use Apiera\Sdk\DTO\QueryParameters;
use Apiera\Sdk\DTO\Request\Attribute\AttributeRequest;
use Apiera\Sdk\DTO\Response\Attribute\AttributeResponse;
use Apiera\Sdk\DTO\Response\Attribute\AttributeCollectionResponse;
use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Exception\InvalidRequestException;
use Apiera\Sdk\Interface\ClientExceptionInterface;
use Apiera\Sdk\Resource\AttributeResource;
use DateTimeImmutable;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Uid\Uuid;

class AttributeResourceTest extends TestCase
{
    private Client|MockObject $clientMock;
    private AttributeDataMapper|MockObject $mapperMock;
    private AttributeResource $resource;
    private AttributeRequest $request;
    private AttributeResponse $response;
    private AttributeCollectionResponse $collectionResponse;
    private array $mockResponseData;
    private array $mockCollectionData;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->clientMock = $this->createMock(Client::class);
        $this->mapperMock = $this->createMock(AttributeDataMapper::class);
        $this->resource = new AttributeResource($this->clientMock, $this->mapperMock);

        // Setup realistic test data
        $this->request = new AttributeRequest(
            name: 'Color',
            store: '/api/stores/123'
        );

        $uuid = Uuid::fromString('f47ac10b-58cc-4372-a567-0e02b2c3d479');
        $createdAt = new DateTimeImmutable('2024-01-25T12:00:00+00:00');
        $updatedAt = new DateTimeImmutable('2024-01-25T12:00:00+00:00');

        $this->response = new AttributeResponse(
            id: '/api/attributes/456',
            type: LdType::Attribute,
            uuid: $uuid,
            createdAt: $createdAt,
            updatedAt: $updatedAt,
            name: 'Color',
            store: '/api/stores/123'
        );

        $this->mockResponseData = [
            '@id' => '/api/attributes/456',
            '@type' => 'Attribute',
            'uuid' => 'f47ac10b-58cc-4372-a567-0e02b2c3d479',
            'name' => 'Color',
            'store' => '/api/stores/123',
            'createdAt' => '2024-01-25T12:00:00+00:00',
            'updatedAt' => '2024-01-25T12:00:00+00:00'
        ];

        $this->mockCollectionData = [
            '@context' => '/api/contexts/Attribute',
            '@id' => '/api/attributes',
            '@type' => 'Collection',
            'member' => [$this->mockResponseData],
            'totalItems' => 1,
            'view' => '/api/attributes?page=1',
            'firstPage' => '/api/attributes?page=1',
            'lastPage' => '/api/attributes?page=1'
        ];

        $this->collectionResponse = new AttributeCollectionResponse(
            context: '/api/contexts/Attribute',
            id: '/api/attributes',
            type: LdType::Collection,
            members: [$this->response],
            totalItems: 1,
            view: '/api/attributes?page=1',
            firstPage: '/api/attributes?page=1',
            lastPage: '/api/attributes?page=1'
        );
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function testFindRequiresStoreIri(): void
    {
        $this->expectException(InvalidRequestException::class);
        $this->resource->find(new AttributeRequest(name: 'Color'));
    }

    /**
     * @throws ClientExceptionInterface
     * @throws InvalidRequestException
     * @throws Exception
     */
    public function testFindReturnsCollection(): void
    {
        $responseMock = $this->createMock(ResponseInterface::class);

        $this->clientMock->expects($this->once())
            ->method('get')
            ->with('/api/stores/123/attributes')
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
     * @throws ClientExceptionInterface
     * @throws InvalidRequestException
     * @throws Exception
     */
    public function testFindOneByReturnsFirstResult(): void
    {
        $params = new QueryParameters(filters: ['name' => 'Color']);

        $this->clientMock->expects($this->once())
            ->method('get')
            ->with('/api/stores/123/attributes')
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
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function testFindOneByThrowsExceptionWhenEmpty(): void
    {
        $emptyCollectionData = [
            '@context' => '/api/contexts/Attribute',
            '@id' => '/api/attributes',
            '@type' => 'Collection',
            'member' => [],
            'totalItems' => 0
        ];

        $emptyCollection = new AttributeCollectionResponse(
            context: '/api/contexts/Attribute',
            id: '/api/attributes',
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
     * @throws ClientExceptionInterface
     */
    public function testGetRequiresIri(): void
    {
        $this->expectException(InvalidRequestException::class);
        $this->resource->get(new AttributeRequest(name: 'Color'));
    }

    /**
     * @throws ClientExceptionInterface
     * @throws InvalidRequestException
     * @throws Exception
     */
    public function testGetReturnsAttribute(): void
    {
        $request = new AttributeRequest(
            name: 'Color',
            iri: '/api/attributes/456'
        );

        $responseMock = $this->createMock(ResponseInterface::class);

        $this->clientMock->expects($this->once())
            ->method('get')
            ->with('/api/attributes/456')
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
     * @throws ClientExceptionInterface
     */
    public function testCreateRequiresStoreIri(): void
    {
        $this->expectException(InvalidRequestException::class);
        $this->resource->create(new AttributeRequest(name: 'Color'));
    }

    /**
     * @throws ClientExceptionInterface
     * @throws InvalidRequestException
     * @throws Exception
     */
    public function testCreateAttribute(): void
    {
        $requestData = [
            'name' => 'Color'
        ];

        $responseMock = $this->createMock(ResponseInterface::class);

        $this->mapperMock->expects($this->once())
            ->method('toRequestData')
            ->with($this->request)
            ->willReturn($requestData);

        $this->clientMock->expects($this->once())
            ->method('post')
            ->with('/api/stores/123/attributes', $requestData)
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
     * @throws ClientExceptionInterface
     */
    public function testUpdateRequiresIri(): void
    {
        $this->expectException(InvalidRequestException::class);
        $this->resource->update(new AttributeRequest(name: 'Color'));
    }

    /**
     * @throws ClientExceptionInterface
     * @throws InvalidRequestException
     * @throws Exception
     */
    public function testUpdateAttribute(): void
    {
        $request = new AttributeRequest(
            name: 'Updated Color',
            iri: '/api/attributes/456'
        );

        $requestData = ['name' => 'Updated Color'];
        $updatedResponseData = array_merge($this->mockResponseData, ['name' => 'Updated Color']);
        $responseMock = $this->createMock(ResponseInterface::class);

        $this->mapperMock->expects($this->once())
            ->method('toRequestData')
            ->with($request)
            ->willReturn($requestData);

        $this->clientMock->expects($this->once())
            ->method('patch')
            ->with('/api/attributes/456', $requestData)
            ->willReturn($responseMock);

        $this->clientMock->expects($this->once())
            ->method('decodeResponse')
            ->with($responseMock)
            ->willReturn($updatedResponseData);

        $updatedResponse = new AttributeResponse(
            id: '/api/attributes/456',
            type: LdType::Attribute,
            uuid: Uuid::fromString('f47ac10b-58cc-4372-a567-0e02b2c3d479'),
            createdAt: new DateTimeImmutable('2024-01-25T12:00:00+00:00'),
            updatedAt: new DateTimeImmutable('2024-01-25T12:00:00+00:00'),
            name: 'Updated Color',
            store: '/api/stores/123'
        );

        $this->mapperMock->expects($this->once())
            ->method('fromResponse')
            ->with($updatedResponseData)
            ->willReturn($updatedResponse);

        $result = $this->resource->update($request);
        $this->assertSame($updatedResponse, $result);
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function testDeleteRequiresIri(): void
    {
        $this->expectException(InvalidRequestException::class);
        $this->resource->delete(new AttributeRequest(name: 'Color'));
    }

    /**
     * @throws ClientExceptionInterface
     * @throws InvalidRequestException
     * @throws Exception
     */
    public function testDeleteAttribute(): void
    {
        $request = new AttributeRequest(
            name: 'Color',
            iri: '/api/attributes/456'
        );

        $responseMock = $this->createMock(ResponseInterface::class);

        $this->clientMock->expects($this->once())
            ->method('delete')
            ->with('/api/attributes/456')
            ->willReturn($responseMock);

        $this->resource->delete($request);
    }
}
