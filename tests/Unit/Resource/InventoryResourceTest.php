<?php

declare(strict_types=1);

namespace Tests\Unit\Resource;

use Apiera\Sdk\Client;
use Apiera\Sdk\DataMapper\InventoryDataMapper;
use Apiera\Sdk\DTO\QueryParameters;
use Apiera\Sdk\DTO\Request\Inventory\InventoryRequest;
use Apiera\Sdk\DTO\Response\Inventory\InventoryCollectionResponse;
use Apiera\Sdk\DTO\Response\Inventory\InventoryResponse;
use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Exception\InvalidRequestException;
use Apiera\Sdk\Resource\InventoryResource;
use DateTimeImmutable;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Uid\Uuid;

final class InventoryResourceTest extends TestCase
{
    private Client|MockObject $clientMock;
    private InventoryDataMapper|MockObject $mapperMock;
    private InventoryResource $resource;
    private InventoryRequest $request;
    private InventoryResponse $response;
    private InventoryCollectionResponse $collectionResponse;

    /** @var array<string, mixed> */
    private array $mockResponseData;

    /** @var array<string, mixed> */
    private array $mockCollectionData;

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     */
    public function testFindRequiresInventoryLocation(): void
    {
        $this->expectException(InvalidRequestException::class);
        $this->resource->find(new InventoryRequest(quantity: 1, sku: '/api/v1/skus/123'));
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
            ->with('/api/v1/inventory_locations/123/inventories')
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
        $params = new QueryParameters(filters: ['sku' => '/api/v1/skus/123']);

        $this->clientMock->expects($this->once())
            ->method('get')
            ->with('/api/v1/inventory_locations/123/inventories')
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
            '@context' => '/api/contexts/Inventory',
            '@id' => '/api/v1/inventory_locations/123/inventories',
            '@type' => 'Collection',
            'member' => [],
            'totalItems' => 0,
        ];

        $emptyCollection = new InventoryCollectionResponse(
            context: '/api/v1/contexts/Inventory',
            id: '/api/v1/inventory_locations/123/inventories',
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
        $this->resource->get(new InventoryRequest(quantity: 1, sku: '/api/v1/skus/123'));
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testGetReturnsInventory(): void
    {
        $request = new InventoryRequest(
            quantity: 1,
            sku: '/api/v1/skus/123',
            inventoryLocation: '/api/v1/inventory_locations/123',
            iri: '/api/v1/inventory_locations/123/inventories/456'
        );

        $responseMock = $this->createMock(ResponseInterface::class);

        $this->clientMock->expects($this->once())
            ->method('get')
            ->with('/api/v1/inventory_locations/123/inventories/456')
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
    public function testCreateRequiresInventoryLocation(): void
    {
        $this->expectException(InvalidRequestException::class);
        $this->resource->create(new InventoryRequest(quantity: 1, sku: '/api/v1/skus/123'));
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testCreateInventory(): void
    {
        $requestData = [
            'quantity' => 1,
            'sku' => '/api/v1/skus/123',
        ];

        $responseMock = $this->createMock(ResponseInterface::class);

        $this->mapperMock->expects($this->once())
            ->method('toRequestData')
            ->with($this->request)
            ->willReturn($requestData);

        $this->clientMock->expects($this->once())
            ->method('post')
            ->with('/api/v1/inventory_locations/123/inventories', $requestData)
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
        $this->resource->update(new InventoryRequest(quantity: 1, sku: '/api/v1/skus/123'));
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testUpdateInventory(): void
    {
        $request = new InventoryRequest(
            quantity: 2,
            sku: '/api/v1/skus/123',
            inventoryLocation: '/api/v1/inventory_locations/123',
            iri: '/api/v1/inventory_locations/123/inventories/456'
        );

        $requestData = ['quantity' => 2, 'sku' => '/api/v1/skus/123'];
        $updatedResponseData = array_merge($this->mockResponseData, ['quantity' => 2]);
        $responseMock = $this->createMock(ResponseInterface::class);

        $this->mapperMock->expects($this->once())
            ->method('toRequestData')
            ->with($request)
            ->willReturn($requestData);

        $this->clientMock->expects($this->once())
            ->method('patch')
            ->with('/api/v1/inventory_locations/123/inventories/456', $requestData)
            ->willReturn($responseMock);

        $this->clientMock->expects($this->once())
            ->method('decodeResponse')
            ->with($responseMock)
            ->willReturn($updatedResponseData);

        $updatedResponse = new InventoryResponse(
            id: '/api/v1/inventory_locations/123/inventories/456',
            type: LdType::Inventory,
            uuid: Uuid::fromString('f47ac10b-58cc-4372-a567-0e02b2c3d479'),
            createdAt: new DateTimeImmutable('2024-01-25T12:00:00+00:00'),
            updatedAt: new DateTimeImmutable('2024-01-25T12:00:00+00:00'),
            quantity: 2,
            sku: '/api/v1/skus/123',
            inventoryLocation: '/api/v1/inventory_locations/123'
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
        $this->resource->delete(new InventoryRequest(quantity: 1, sku: '/api/v1/skus/123'));
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testDeleteInventory(): void
    {
        $request = new InventoryRequest(
            quantity: 1,
            sku: '/api/v1/skus/123',
            inventoryLocation: '/api/v1/inventory_locations/123',
            iri: '/api/v1/inventory_locations/123/inventories/456'
        );

        $responseMock = $this->createMock(ResponseInterface::class);

        $this->clientMock->expects($this->once())
            ->method('delete')
            ->with('/api/v1/inventory_locations/123/inventories/456')
            ->willReturn($responseMock);

        $this->resource->delete($request);
    }

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    protected function setUp(): void
    {
        $this->clientMock = $this->createMock(Client::class);
        $this->mapperMock = $this->createMock(InventoryDataMapper::class);
        $this->resource = new InventoryResource($this->clientMock, $this->mapperMock);

        // Setup realistic test data
        $this->request = new InventoryRequest(
            quantity: 1,
            sku: '/api/v1/skus/123',
            inventoryLocation: '/api/v1/inventory_locations/123'
        );

        $uuid = Uuid::fromString('f47ac10b-58cc-4372-a567-0e02b2c3d479');
        $createdAt = new DateTimeImmutable('2024-01-25T12:00:00+00:00');
        $updatedAt = new DateTimeImmutable('2024-01-25T12:00:00+00:00');

        $this->response = new InventoryResponse(
            id: '/api/v1/inventory_locations/123/inventories/456',
            type: LdType::Inventory,
            uuid: $uuid,
            createdAt: $createdAt,
            updatedAt: $updatedAt,
            quantity: 1,
            sku: '/api/v1/skus/123',
            inventoryLocation: '/api/v1/inventory_locations/123'
        );

        $this->mockResponseData = [
            '@id' => '/api/v1/inventory_locations/123/inventories/456',
            '@type' => 'Inventory',
            'uuid' => 'f47ac10b-58cc-4372-a567-0e02b2c3d479',
            'quantity' => 1,
            'sku' => '/api/v1/skus/123',
            'inventoryLocation' => '/api/v1/inventory_locations/123',
            'createdAt' => '2024-01-25T12:00:00+00:00',
            'updatedAt' => '2024-01-25T12:00:00+00:00',
        ];

        $this->mockCollectionData = [
            '@context' => '/api/v1/contexts/Inventory',
            '@id' => '/api/v1/inventory_locations/123/inventories',
            '@type' => 'Collection',
            'member' => [$this->mockResponseData],
            'totalItems' => 1,
            'view' => '/api/v1/inventory_locations/123/inventories?page=1',
            'firstPage' => '/api/v1/inventory_locations/123/inventories?page=1',
            'lastPage' => '/api/v1/inventory_locations/123/inventories?page=1',
        ];

        $this->collectionResponse = new InventoryCollectionResponse(
            context: '/api/v1/contexts/Inventory',
            id: '/api/v1/inventory_locations/123/inventories',
            type: LdType::Collection,
            members: [$this->response],
            totalItems: 1,
            view: '/api/v1/inventory_locations/123/inventories?page=1',
            firstPage: '/api/v1/inventory_locations/123/inventories?page=1',
            lastPage: '/api/v1/inventory_locations/123/inventories?page=1'
        );
    }
}
