<?php

declare(strict_types=1);

namespace Tests\Unit\DataMapper;

use Apiera\Sdk\DataMapper\ReflectionAttributeDataMapper;
use Apiera\Sdk\DTO\Request\Product\ProductRequest;
use Apiera\Sdk\DTO\Response\Product\ProductCollectionResponse;
use Apiera\Sdk\DTO\Response\Product\ProductResponse;
use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Enum\ProductStatus;
use Apiera\Sdk\Enum\ProductType;
use Apiera\Sdk\Exception\Mapping\ResponseMappingException;
use DateTimeInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

final class ProductDataMapperTest extends TestCase
{
    private ReflectionAttributeDataMapper $mapper;

    /** @var array<string, mixed> */
    private array $sampleResponseData;

    /** @var array<string, mixed> */
    private array $sampleCollectionData;
    private ProductRequest $sampleRequest;

    /**
     * @throws \Apiera\Sdk\Exception\Mapping\ResponseMappingException
     */
    public function testFromResponseMapsAllFieldsCorrectly(): void
    {
        /** @var ProductResponse $result */
        $result = $this->mapper->fromResponse($this->sampleResponseData);

        $this->assertInstanceOf(ProductResponse::class, $result);
        $this->assertEquals('/api/v1/stores/123/products/123', $result->getLdId());
        $this->assertEquals(LdType::Product, $result->getLdType());
        $this->assertEquals(Uuid::fromString('123e4567-e89b-12d3-a456-426614174000'), $result->getUuid());
        $this->assertInstanceOf(DateTimeInterface::class, $result->getCreatedAt());
        $this->assertEquals('2025-01-01T00:00:00+00:00', $result->getCreatedAt()->format(DATE_ATOM));
        $this->assertInstanceOf(DateTimeInterface::class, $result->getUpdatedAt());
        $this->assertEquals('2025-01-01T00:00:00+00:00', $result->getUpdatedAt()->format(DATE_ATOM));
        $this->assertEquals(ProductType::Simple, $result->getType());
        $this->assertEquals(ProductStatus::Active, $result->getStatus());
        $this->assertEquals('/api/v1/stores/123', $result->getStore());
        $this->assertEquals('/api/v1/skus/123', $result->getSku());
        $this->assertEquals('Test product', $result->getName());
        $this->assertEquals('100.00', $result->getPrice());
        $this->assertEquals('90.00', $result->getSalePrice());
        $this->assertEquals('Test product description', $result->getDescription());
        $this->assertEquals('Test product short description', $result->getShortDescription());
        $this->assertEquals('100.00', $result->getWeight());
        $this->assertEquals('100.00', $result->getLength());
        $this->assertEquals('100.00', $result->getWidth());
        $this->assertEquals('100.00', $result->getHeight());
        $this->assertEquals('/api/v1/stores/123/distributors/123', $result->getDistributor());
        $this->assertEquals('/api/v1/stores/123/brands/123', $result->getBrand());
        $this->assertEquals('/api/v1/files/123', $result->getImage());
        $this->assertEquals(
            ['/api/v1/stores/123/categories/123', '/api/v1/stores/123/categories/456'],
            $result->getCategories()
        );
        $this->assertEquals(
            ['/api/v1/stores/123/tags/123', '/api/v1/stores/123/tags/456'],
            $result->getTags()
        );
        $this->assertEquals(
            ['/api/v1/stores/123/attributes/123', '/api/v1/stores/123/attributes/456'],
            $result->getAttributes()
        );
        $this->assertEquals(
            ['/api/v1/files/456', '/api/v1/files/789'],
            $result->getImages()
        );
        $this->assertEquals(
            [
                '/api/v1/alternate_identifiers/123',
                '/api/v1/alternate_identifiers/456',
                '/api/v1/alternate_identifiers/789',
            ],
            $result->getAlternateIdentifiers()
        );
        $this->assertEquals(
            [
                '/api/v1/stores/123/properties/123/terms/123',
                '/api/v1/stores/123/properties/456/terms/456',
            ],
            $result->getPropertyTerms()
        );
    }

    /**
     * @throws \Apiera\Sdk\Exception\Mapping\ResponseMappingException
     */
    public function testFromResponseThrowsExceptionForInvalidDate(): void
    {
        $data = $this->sampleResponseData;
        $data['createdAt'] = 'invalid-date';

        $this->expectException(ResponseMappingException::class);
        $this->mapper->fromResponse($data);
    }

    /**
     * @throws \Apiera\Sdk\Exception\Mapping\ResponseMappingException
     */
    public function testFromCollectionResponseThrowsExceptionForInvalidType(): void
    {
        $data = $this->sampleCollectionData;
        $data['@type'] = 'InvalidType';

        $this->expectException(ResponseMappingException::class);
        $this->expectExceptionMessage('Failed to map collection data');
        $this->mapper->fromCollectionResponse($data);
    }

    /**
     * @throws \Apiera\Sdk\Exception\Mapping\ResponseMappingException
     */
    public function testFromCollectionResponseMapsDataCorrectly(): void
    {
        $result = $this->mapper->fromCollectionResponse($this->sampleCollectionData);

        $this->assertInstanceOf(ProductCollectionResponse::class, $result);
        $this->assertEquals('/api/v1/contexts/Product', $result->getLdContext());
        $this->assertEquals('/api/v1/stores/123/products', $result->getLdId());
        $this->assertEquals(LdType::Collection, $result->getLdType());
        $this->assertEquals(1, $result->getTotalItems());
        $this->assertCount(1, $result->getMembers());
        $this->assertInstanceOf(ProductResponse::class, $result->getMembers()[0]);
        $this->assertEquals('/api/v1/stores/123/products/123', $result->getMembers()[0]->getLdId());
        $this->assertEquals(LdType::Product, $result->getMembers()[0]->getLdType());
        $this->assertEquals(
            Uuid::fromString('123e4567-e89b-12d3-a456-426614174000'),
            $result->getMembers()[0]->getUuid()
        );
    }

    /**
     * @throws \Apiera\Sdk\Exception\Mapping\ResponseMappingException
     */
    public function testFromCollectionResponseHandlesEmptyCollection(): void
    {
        $data = $this->sampleCollectionData;
        $data['member'] = [];
        $data['totalItems'] = 0;

        $result = $this->mapper->fromCollectionResponse($data);

        $this->assertEmpty($result->getMembers());
        $this->assertEquals(0, $result->getTotalItems());
    }

    /**
     * @throws \Apiera\Sdk\Exception\Mapping\RequestMappingException
     */
    public function testToRequestDataIncludesRequiredFields(): void
    {
        $result = $this->mapper->toRequestData($this->sampleRequest);

        $expected = [
            'type' => 'simple',
            'status' => 'active',
            'sku' => '/api/v1/skus/123',
            'name' => null,
            'price' => null,
            'salePrice' => null,
            'description' => null,
            'shortDescription' => null,
            'weight' => null,
            'length' => null,
            'width' => null,
            'height' => null,
            'distributor' => null,
            'brand' => null,
            'image' => null,
            'categories' => [],
            'tags' => [],
            'attributes' => [],
            'images' => [],
            'alternateIdentifiers' => [],
            'propertyTerms' => [],
        ];

        $this->assertEquals($expected, $result);
    }

    /**
     * @throws \Apiera\Sdk\Exception\Mapping\ResponseMappingException
     */
    public function testFromCollectionResponseWithInvalidMemberThrowsException(): void
    {
        $data = $this->sampleCollectionData;
        $data['member'][0]['createdAt'] = 'invalid-date';

        $this->expectException(ResponseMappingException::class);
        $this->mapper->fromCollectionResponse($data);
    }

    protected function setUp(): void
    {
        $this->mapper = new ReflectionAttributeDataMapper();

        $this->sampleResponseData = [
            '@id' => '/api/v1/stores/123/products/123',
            '@type' => 'Product',
            'uuid' => '123e4567-e89b-12d3-a456-426614174000',
            'createdAt' => '2025-01-01T00:00:00+00:00',
            'updatedAt' => '2025-01-01T00:00:00+00:00',
            'type' => 'simple',
            'status' => 'active',
            'store' => '/api/v1/stores/123',
            'sku' => '/api/v1/skus/123',
            'name' => 'Test product',
            'price' => '100.00',
            'salePrice' => '90.00',
            'description' => 'Test product description',
            'shortDescription' => 'Test product short description',
            'weight' => '100.00',
            'length' => '100.00',
            'width' => '100.00',
            'height' => '100.00',
            'distributor' => '/api/v1/stores/123/distributors/123',
            'brand' => '/api/v1/stores/123/brands/123',
            'image' => '/api/v1/files/123',
            'categories' => [
                '/api/v1/stores/123/categories/123',
                '/api/v1/stores/123/categories/456',
            ],
            'tags' => [
                '/api/v1/stores/123/tags/123',
                '/api/v1/stores/123/tags/456',
            ],
            'attributes' => [
                '/api/v1/stores/123/attributes/123',
                '/api/v1/stores/123/attributes/456',
            ],
            'images' => [
                '/api/v1/files/456',
                '/api/v1/files/789',
            ],
            'alternateIdentifiers' => [
                '/api/v1/alternate_identifiers/123',
                '/api/v1/alternate_identifiers/456',
                '/api/v1/alternate_identifiers/789',
            ],
            'propertyTerms' => [
                '/api/v1/stores/123/properties/123/terms/123',
                '/api/v1/stores/123/properties/456/terms/456',
            ],
        ];

        $this->sampleCollectionData = [
            '@context' => '/api/v1/contexts/Product',
            '@id' => '/api/v1/stores/123/products',
            '@type' => 'Collection',
            'member' => [$this->sampleResponseData],
            'totalItems' => 1,
            'view' => '/api/v1/stores/123/products?page=1',
            'firstPage' => '/api/v1/stores/123/products?page=1',
            'lastPage' => '/api/v1/stores/123/products?page=1',
            'nextPage' => null,
            'previousPage' => null,
        ];

        $this->sampleRequest = new ProductRequest(
            type: ProductType::Simple,
            status: ProductStatus::Active,
            sku: '/api/v1/skus/123',
            store: '/api/v1/stores/123',
        );
    }
}
