<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Response\Product;

use Apiera\Sdk\DTO\Response\AbstractCollectionResponse;
use Apiera\Sdk\DTO\Response\Product\ProductCollectionResponse;
use Apiera\Sdk\DTO\Response\Product\ProductResponse;
use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Enum\ProductStatus;
use Apiera\Sdk\Enum\ProductType;
use Apiera\Sdk\Interface\DTO\JsonLDCollectionInterface;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Symfony\Component\Uid\Uuid;

final class ProductCollectionResponseTest extends TestCase
{
    public function testInstanceOf(): void
    {
        $response = new ProductCollectionResponse(
            context: '',
            id: '',
            type: LdType::Collection,
            members: [],
            totalItems: 0
        );

        $this->assertInstanceOf(ProductCollectionResponse::class, $response);
        $this->assertInstanceOf(AbstractCollectionResponse::class, $response);
        $this->assertInstanceOf(JsonLDCollectionInterface::class, $response);
    }

    public function testClassIsCorrectlyDefined(): void
    {
        $reflection = new ReflectionClass(ProductCollectionResponse::class);

        $this->assertTrue($reflection->isReadonly(), 'Class should be readonly');
    }

    public function testConstructorAndGetters(): void
    {
        $productResponse = new ProductResponse(
            ldId: '/api/v1/stores/123/products/456',
            ldType: LdType::Product,
            uuid: Uuid::fromString('bfd2639c-7793-426a-a413-ea262e582208'),
            createdAt: new DateTimeImmutable('2021-01-01 00:00:00'),
            updatedAt: new DateTimeImmutable('2021-01-01 00:00:00'),
            type: ProductType::Simple,
            status: ProductStatus::Active,
            store: '/api/v1/stores/123',
            sku: '/api/v1/skus/789',
            name: 'Test Product',
            price: '99.99'
        );

        $response = new ProductCollectionResponse(
            context: '/api/v1/contexts/Product',
            id: '/api/v1/stores/123/products',
            type: LdType::Collection,
            members: [$productResponse],
            totalItems: 1,
            view: '/api/v1/stores/123/products?page=1',
            firstPage: '/api/v1/stores/123/products?page=1',
            lastPage: '/api/v1/stores/123/products?page=1',
            nextPage: null,
            previousPage: null
        );

        $this->assertEquals('/api/v1/contexts/Product', $response->getLdContext());
        $this->assertEquals('/api/v1/stores/123/products', $response->getLdId());
        $this->assertEquals(LdType::Collection, $response->getLdType());
        $this->assertCount(1, $response->getMembers());
        $this->assertInstanceOf(ProductResponse::class, $response->getMembers()[0]);
        $this->assertEquals(1, $response->getTotalItems());
        $this->assertEquals('/api/v1/stores/123/products?page=1', $response->getView());
        $this->assertEquals('/api/v1/stores/123/products?page=1', $response->getFirstPage());
        $this->assertEquals('/api/v1/stores/123/products?page=1', $response->getLastPage());
        $this->assertNull($response->getNextPage());
        $this->assertNull($response->getPreviousPage());
    }

    public function testConstructorWithMinimalParameters(): void
    {
        $response = new ProductCollectionResponse(
            context: '/api/v1/contexts/Product',
            id: '/api/v1/stores/123/products',
            type: LdType::Collection,
            members: [],
            totalItems: 0
        );

        $this->assertEquals('/api/v1/contexts/Product', $response->getLdContext());
        $this->assertEquals('/api/v1/stores/123/products', $response->getLdId());
        $this->assertEquals(LdType::Collection, $response->getLdType());
        $this->assertEmpty($response->getMembers());
        $this->assertEquals(0, $response->getTotalItems());
        $this->assertNull($response->getView());
        $this->assertNull($response->getFirstPage());
        $this->assertNull($response->getLastPage());
        $this->assertNull($response->getNextPage());
        $this->assertNull($response->getPreviousPage());
    }
}
