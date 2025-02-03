<?php

declare(strict_types=1);

namespace Tests\Unit\Resource;

use Apiera\Sdk\Client;
use Apiera\Sdk\DataMapper\ReflectionAttributeDataMapper;
use Apiera\Sdk\DTO\QueryParameters;
use Apiera\Sdk\DTO\Request\Product\ProductRequest;
use Apiera\Sdk\DTO\Response\Product\ProductCollectionResponse;
use Apiera\Sdk\DTO\Response\Product\ProductResponse;
use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Enum\ProductStatus;
use Apiera\Sdk\Enum\ProductType;
use Apiera\Sdk\Exception\InvalidRequestException;
use Apiera\Sdk\Resource\ProductResource;
use DateTimeImmutable;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Uid\Uuid;

final class ProductResourceTest extends TestCase
{
    private MockObject $clientMock;
    private MockObject $mapperMock;
    private ProductResource $resource;
    private ProductRequest $request;
    private ProductResponse $response;
    private ProductCollectionResponse $collectionResponse;

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
        $this->resource->find(
            new ProductRequest(
                type: ProductType::Simple,
                status: ProductStatus::Active,
                sku: '/api/v1/skus/123'
            )
        );
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function testFindReturnsCollection(): void
    {
        $responseMock = $this->createMock(ResponseInterface::class);

        $this->clientMock->expects($this->once())
            ->method('get')
            ->with('/api/v1/stores/123/products')
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

        $this->assertEquals(1, $result->getTotalItems());
        $this->assertCount(1, $result->getMembers());
        $this->assertEquals('Test Product', $result->getMembers()[0]->getName());
        $this->assertEquals('99.99', $result->getMembers()[0]->getPrice());
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function testFindOneByReturnsFirstResult(): void
    {
        $params = new QueryParameters(filters: ['name' => 'Test Product']);

        $this->clientMock->expects($this->once())
            ->method('get')
            ->with('/api/v1/stores/123/products')
            ->willReturn($this->createMock(ResponseInterface::class));

        $this->clientMock->expects($this->once())
            ->method('decodeResponse')
            ->willReturn($this->mockCollectionData);

        $this->mapperMock->expects($this->once())
            ->method('fromCollectionResponse')
            ->willReturn($this->collectionResponse);

        $result = $this->resource->findOneBy($this->request, $params);

        $this->assertEquals('Test Product', $result->getName());
        $this->assertEquals('99.99', $result->getPrice());
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function testFindOneByThrowsExceptionWhenEmpty(): void
    {
        $emptyCollectionData = [
            '@context' => '/api/contexts/Product',
            '@id' => '/api/v1/stores/123/products',
            '@type' => 'Collection',
            'member' => [],
            'totalItems' => 0,
        ];

        $emptyCollection = new ProductCollectionResponse(
            context: '/api/contexts/Product',
            id: '/api/v1/stores/123/products',
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
        $this->resource->get(
            new ProductRequest(
                type: ProductType::Simple,
                status: ProductStatus::Active,
                sku: '/api/v1/skus/123'
            )
        );
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function testGetReturnsProduct(): void
    {
        $request = new ProductRequest(
            type: ProductType::Simple,
            status: ProductStatus::Active,
            sku: '/api/v1/skus/123',
            iri: '/api/v1/stores/123/products/456'
        );

        $responseMock = $this->createMock(ResponseInterface::class);

        $this->clientMock->expects($this->once())
            ->method('get')
            ->with('/api/v1/stores/123/products/456')
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

        $this->assertEquals('Test Product', $result->getName());
        $this->assertEquals('99.99', $result->getPrice());
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function testCreateRequiresStoreIri(): void
    {
        $this->expectException(InvalidRequestException::class);
        $this->resource->create(
            new ProductRequest(
                type: ProductType::Simple,
                status: ProductStatus::Active,
                sku: '/api/v1/skus/123'
            )
        );
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function testCreateProduct(): void
    {
        $requestData = [
            'type' => 'simple',
            'status' => 'active',
            'sku' => '/api/v1/skus/123',
            'name' => 'Test Product',
            'price' => '99.99',
        ];

        $responseMock = $this->createMock(ResponseInterface::class);

        $this->mapperMock->expects($this->once())
            ->method('toRequestData')
            ->with($this->request)
            ->willReturn($requestData);

        $this->clientMock->expects($this->once())
            ->method('post')
            ->with('/api/v1/stores/123/products', $requestData)
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

        $this->assertEquals('Test Product', $result->getName());
        $this->assertEquals('99.99', $result->getPrice());
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function testUpdateRequiresIri(): void
    {
        $this->expectException(InvalidRequestException::class);
        $this->resource->update(
            new ProductRequest(
                type: ProductType::Simple,
                status: ProductStatus::Active,
                sku: '/api/v1/skus/123'
            )
        );
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function testUpdateProduct(): void
    {
        $request = new ProductRequest(
            type: ProductType::Simple,
            status: ProductStatus::Active,
            sku: '/api/v1/skus/123',
            name: 'Updated Product',
            price: '149.99',
            iri: '/api/v1/stores/123/products/456'
        );

        $requestData = [
            'type' => 'simple',
            'status' => 'active',
            'sku' => '/api/v1/skus/123',
            'name' => 'Updated Product',
            'price' => '149.99',
        ];

        $updatedResponseData = array_merge($this->mockResponseData, [
            'name' => 'Updated Product',
            'price' => '149.99',
        ]);

        $responseMock = $this->createMock(ResponseInterface::class);

        $this->mapperMock->expects($this->once())
            ->method('toRequestData')
            ->with($request)
            ->willReturn($requestData);

        $this->clientMock->expects($this->once())
            ->method('patch')
            ->with('/api/v1/stores/123/products/456', $requestData)
            ->willReturn($responseMock);

        $this->clientMock->expects($this->once())
            ->method('decodeResponse')
            ->with($responseMock)
            ->willReturn($updatedResponseData);

        $updatedResponse = new ProductResponse(
            ldId: '/api/v1/stores/123/products/456',
            ldType: LdType::Product,
            uuid: Uuid::fromString('f47ac10b-58cc-4372-a567-0e02b2c3d479'),
            createdAt: new DateTimeImmutable('2024-01-25T12:00:00+00:00'),
            updatedAt: new DateTimeImmutable('2024-01-25T12:00:00+00:00'),
            type: ProductType::Simple,
            status: ProductStatus::Active,
            store: '/api/v1/stores/123',
            sku: '/api/v1/skus/123',
            name: 'Updated Product',
            price: '149.99'
        );

        $this->mapperMock->expects($this->once())
            ->method('fromResponse')
            ->with($updatedResponseData)
            ->willReturn($updatedResponse);

        $result = $this->resource->update($request);

        $this->assertEquals('Updated Product', $result->getName());
        $this->assertEquals('149.99', $result->getPrice());
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     */
    public function testDeleteRequiresIri(): void
    {
        $this->expectException(InvalidRequestException::class);
        $this->resource->delete(
            new ProductRequest(
                type: ProductType::Simple,
                status: ProductStatus::Active,
                sku: '/api/v1/skus/123'
            )
        );
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     */
    public function testDeleteProduct(): void
    {
        $request = new ProductRequest(
            type: ProductType::Simple,
            status: ProductStatus::Active,
            sku: '/api/v1/skus/123',
            iri: '/api/v1/stores/123/products/456'
        );

        $responseMock = $this->createMock(ResponseInterface::class);

        $this->clientMock->expects($this->once())
            ->method('delete')
            ->with('/api/v1/stores/123/products/456')
            ->willReturn($responseMock);

        $this->resource->delete($request);
    }

    protected function setUp(): void
    {
        /** @phpstan-ignore-next-line */
        $this->clientMock = $this->createMock(Client::class);
        /** @phpstan-ignore-next-line */
        $this->mapperMock = $this->createMock(ReflectionAttributeDataMapper::class);
        $this->resource = new ProductResource($this->clientMock, $this->mapperMock);

        // Setup realistic test data
        $this->request = new ProductRequest(
            type: ProductType::Simple,
            status: ProductStatus::Active,
            sku: '/api/v1/skus/123',
            name: 'Test Product',
            price: '99.99',
            store: '/api/v1/stores/123'
        );

        $uuid = Uuid::fromString('f47ac10b-58cc-4372-a567-0e02b2c3d479');
        $createdAt = new DateTimeImmutable('2024-01-25T12:00:00+00:00');
        $updatedAt = new DateTimeImmutable('2024-01-25T12:00:00+00:00');

        $this->response = new ProductResponse(
            ldId: '/api/v1/stores/123/products/456',
            ldType: LdType::Product,
            uuid: $uuid,
            createdAt: $createdAt,
            updatedAt: $updatedAt,
            type: ProductType::Simple,
            status: ProductStatus::Active,
            store: '/api/v1/stores/123',
            sku: '/api/v1/skus/123',
            name: 'Test Product',
            price: '99.99'
        );

        $this->mockResponseData = [
            '@id' => '/api/v1/stores/123/products/456',
            '@type' => 'Product',
            'uuid' => 'f47ac10b-58cc-4372-a567-0e02b2c3d479',
            'createdAt' => '2024-01-25T12:00:00+00:00',
            'updatedAt' => '2024-01-25T12:00:00+00:00',
            'type' => 'simple',
            'status' => 'active',
            'store' => '/api/v1/stores/123',
            'sku' => '/api/v1/skus/123',
            'name' => 'Test Product',
            'price' => '99.99',
        ];

        $this->mockCollectionData = [
            '@context' => '/api/contexts/Product',
            '@id' => '/api/v1/stores/123/products',
            '@type' => 'Collection',
            'member' => [$this->mockResponseData],
            'totalItems' => 1,
            'view' => '/api/v1/stores/123/products?page=1',
            'firstPage' => '/api/v1/stores/123/products?page=1',
            'lastPage' => '/api/v1/stores/123/products?page=1',
            'nextPage' => null,
            'previousPage' => null,
        ];

        $this->collectionResponse = new ProductCollectionResponse(
            context: '/api/contexts/Product',
            id: '/api/v1/stores/123/products',
            type: LdType::Collection,
            members: [$this->response],
            totalItems: 1,
            view: '/api/v1/stores/123/products?page=1',
            firstPage: '/api/v1/stores/123/products?page=1',
            lastPage: '/api/v1/stores/123/products?page=1',
            nextPage: null,
            previousPage: null
        );
    }
}
