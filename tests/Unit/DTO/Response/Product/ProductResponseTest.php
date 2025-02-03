<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Response\Product;

use Apiera\Sdk\DTO\Response\AbstractResponse;
use Apiera\Sdk\DTO\Response\Product\ProductResponse;
use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Enum\ProductStatus;
use Apiera\Sdk\Enum\ProductType;
use Apiera\Sdk\Interface\DTO\JsonLDInterface;
use Apiera\Sdk\Interface\DTO\ResponseInterface;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Symfony\Component\Uid\Uuid;

final class ProductResponseTest extends TestCase
{
    public function testInstanceOf(): void
    {
        $response = new ProductResponse(
            ldId: '',
            ldType: LdType::Product,
            uuid: Uuid::v4(),
            createdAt: new DateTimeImmutable(),
            updatedAt: new DateTimeImmutable(),
            type: ProductType::Simple,
            status: ProductStatus::Active,
            store: '',
            sku: ''
        );

        $this->assertInstanceOf(ProductResponse::class, $response);
        $this->assertInstanceOf(AbstractResponse::class, $response);
        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertInstanceOf(JsonLDInterface::class, $response);
    }

    public function testClassIsCorrectlyDefined(): void
    {
        $reflection = new ReflectionClass(ProductResponse::class);

        $this->assertTrue($reflection->isReadonly(), 'Class should be readonly');
    }

    public function testPropertiesAreReadonly(): void
    {
        $reflection = new ReflectionClass(ProductResponse::class);
        $properties = $reflection->getProperties();

        foreach ($properties as $property) {
            $this->assertTrue($property->isReadonly(), sprintf('Property %s should be readonly', $property->getName()));
        }
    }

    public function testConstructorAndGetters(): void
    {
        $response = new ProductResponse(
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
            price: '99.99',
            salePrice: '79.99',
            description: 'Full description',
            shortDescription: 'Short description',
            weight: '1.50',
            length: '10.00',
            width: '5.00',
            height: '2.00',
            distributor: '/api/v1/stores/123/distributors/456',
            brand: '/api/v1/stores/123/brands/789',
            image: '/api/v1/files/123',
            categories: ['/api/v1/stores/123/categories/456'],
            tags: ['/api/v1/stores/123/tags/789'],
            attributes: ['/api/v1/stores/123/attributes/012'],
            images: ['/api/v1/files/456'],
            alternateIdentifiers: ['/api/v1/alternate_identifiers/345'],
            propertyTerms: ['/api/v1/stores/123/properties/456/terms/789']
        );

        // Test basic response fields
        $this->assertEquals('/api/v1/stores/123/products/456', $response->getLdId());
        $this->assertEquals(LdType::Product, $response->getLdType());
        $this->assertTrue(Uuid::isValid($response->getUuid()->toRfc4122()));
        $this->assertEquals('bfd2639c-7793-426a-a413-ea262e582208', $response->getUuid()->toRfc4122());
        $this->assertEquals(new DateTimeImmutable('2021-01-01 00:00:00'), $response->getCreatedAt());
        $this->assertEquals(new DateTimeImmutable('2021-01-01 00:00:00'), $response->getUpdatedAt());

        // Test product-specific fields
        $this->assertEquals(ProductType::Simple, $response->getType());
        $this->assertEquals(ProductStatus::Active, $response->getStatus());
        $this->assertEquals('/api/v1/stores/123', $response->getStore());
        $this->assertEquals('/api/v1/skus/789', $response->getSku());
        $this->assertEquals('Test Product', $response->getName());
        $this->assertEquals('99.99', $response->getPrice());
        $this->assertEquals('79.99', $response->getSalePrice());
        $this->assertEquals('Full description', $response->getDescription());
        $this->assertEquals('Short description', $response->getShortDescription());
        $this->assertEquals('1.50', $response->getWeight());
        $this->assertEquals('10.00', $response->getLength());
        $this->assertEquals('5.00', $response->getWidth());
        $this->assertEquals('2.00', $response->getHeight());
        $this->assertEquals('/api/v1/stores/123/distributors/456', $response->getDistributor());
        $this->assertEquals('/api/v1/stores/123/brands/789', $response->getBrand());
        $this->assertEquals('/api/v1/files/123', $response->getImage());

        // Test array fields
        $this->assertEquals(['/api/v1/stores/123/categories/456'], $response->getCategories());
        $this->assertEquals(['/api/v1/stores/123/tags/789'], $response->getTags());
        $this->assertEquals(['/api/v1/stores/123/attributes/012'], $response->getAttributes());
        $this->assertEquals(['/api/v1/files/456'], $response->getImages());
        $this->assertEquals(['/api/v1/alternate_identifiers/345'], $response->getAlternateIdentifiers());
        $this->assertEquals(['/api/v1/stores/123/properties/456/terms/789'], $response->getPropertyTerms());
    }

    public function testConstructorWithMinimalParameters(): void
    {
        $response = new ProductResponse(
            ldId: '/api/v1/stores/123/products/456',
            ldType: LdType::Product,
            uuid: Uuid::fromString('bfd2639c-7793-426a-a413-ea262e582208'),
            createdAt: new DateTimeImmutable('2021-01-01 00:00:00'),
            updatedAt: new DateTimeImmutable('2021-01-01 00:00:00'),
            type: ProductType::Simple,
            status: ProductStatus::Active,
            store: '/api/v1/stores/123',
            sku: '/api/v1/skus/789'
        );

        // Test required fields
        $this->assertEquals('/api/v1/stores/123/products/456', $response->getLdId());
        $this->assertEquals(LdType::Product, $response->getLdType());
        $this->assertEquals('bfd2639c-7793-426a-a413-ea262e582208', $response->getUuid()->toRfc4122());
        $this->assertEquals(new DateTimeImmutable('2021-01-01 00:00:00'), $response->getCreatedAt());
        $this->assertEquals(new DateTimeImmutable('2021-01-01 00:00:00'), $response->getUpdatedAt());
        $this->assertEquals(ProductType::Simple, $response->getType());
        $this->assertEquals(ProductStatus::Active, $response->getStatus());
        $this->assertEquals('/api/v1/stores/123', $response->getStore());
        $this->assertEquals('/api/v1/skus/789', $response->getSku());

        // Test optional fields are null or empty
        $this->assertNull($response->getName());
        $this->assertNull($response->getPrice());
        $this->assertNull($response->getSalePrice());
        $this->assertNull($response->getDescription());
        $this->assertNull($response->getShortDescription());
        $this->assertNull($response->getWeight());
        $this->assertNull($response->getLength());
        $this->assertNull($response->getWidth());
        $this->assertNull($response->getHeight());
        $this->assertNull($response->getDistributor());
        $this->assertNull($response->getBrand());
        $this->assertNull($response->getImage());
        $this->assertEquals([], $response->getCategories());
        $this->assertEquals([], $response->getTags());
        $this->assertEquals([], $response->getAttributes());
        $this->assertEquals([], $response->getImages());
        $this->assertEquals([], $response->getAlternateIdentifiers());
        $this->assertEquals([], $response->getPropertyTerms());
    }
}
