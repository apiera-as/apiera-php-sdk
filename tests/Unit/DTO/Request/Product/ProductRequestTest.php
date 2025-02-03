<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Request\Product;

use Apiera\Sdk\DTO\Request\Product\ProductRequest;
use Apiera\Sdk\Enum\ProductStatus;
use Apiera\Sdk\Enum\ProductType;
use Apiera\Sdk\Interface\DTO\RequestInterface;
use PHPUnit\Framework\TestCase;

final class ProductRequestTest extends TestCase
{
    public function testInstanceOf(): void
    {
        $request = new ProductRequest();

        $this->assertInstanceOf(ProductRequest::class, $request);
        $this->assertInstanceOf(RequestInterface::class, $request);
    }

    public function testConstructorAndGetters(): void
    {
        $request = new ProductRequest(
            name: 'Product name',
            type: ProductType::Simple,
            price: '100.00',
            salePrice: '90.00',
            description: 'Product description',
            shortDescription: 'Product short description',
            weight: '100.00',
            length: '100.00',
            width: '100.00',
            height: '100.00',
            status: ProductStatus::Active,
            distributor: '/api/v1/stores/123/distributors/456',
            brand: '/api/v1/stores/123/brands/456',
            sku: '/api/v1/skus/123',
            image: '/api/v1/files/123',
            categories: [
                '/api/v1/stores/123/categories/456',
                '/api/v1/stores/123/categories/789',
            ],
            tags: [
                '/api/v1/stores/123/tags/456',
                '/api/v1/stores/123/tags/789',
            ],
            attributes: [
                '/api/v1/stores/123/attributes/456',
                '/api/v1/stores/123/attributes/789',
            ],
            images: [
                '/api/v1/files/123',
                '/api/v1/files/456',
            ],
            alternateIdentifiers: [
                '/api/v1/alternate_identifiers/456',
                '/api/v1/alternate_identifiers/789',
            ],
            propertyTerms: [
                '/api/v1/stores/123/properties/456/terms/123',
                '/api/v1/stores/123/properties/456/terms/456',
            ],
            store: '/api/v1/stores/123',
            iri: '/api/v1/stores/123/products/456'
        );


        $this->assertEquals(ProductType::Simple, $request->getType());
        $this->assertEquals(ProductStatus::Active, $request->getStatus());
        $this->assertEquals('/api/v1/skus/123', $request->getSku());
        $this->assertEquals('Product name', $request->getName());
        $this->assertEquals('100.00', $request->getPrice());
        $this->assertEquals('90.00', $request->getSalePrice());
        $this->assertEquals('Product description', $request->getDescription());
        $this->assertEquals('Product short description', $request->getShortDescription());
        $this->assertEquals('100.00', $request->getWeight());
        $this->assertEquals('100.00', $request->getLength());
        $this->assertEquals('100.00', $request->getWidth());
        $this->assertEquals('100.00', $request->getHeight());
        $this->assertEquals('/api/v1/stores/123/distributors/456', $request->getDistributor());
        $this->assertEquals('/api/v1/stores/123/brands/456', $request->getBrand());
        $this->assertEquals('/api/v1/files/123', $request->getImage());
        $this->assertEquals(
            ['/api/v1/stores/123/categories/456', '/api/v1/stores/123/categories/789'],
            $request->getCategories()
        );
        $this->assertEquals(
            ['/api/v1/stores/123/tags/456', '/api/v1/stores/123/tags/789'],
            $request->getTags()
        );
        $this->assertEquals(
            ['/api/v1/stores/123/attributes/456', '/api/v1/stores/123/attributes/789'],
            $request->getAttributes()
        );
        $this->assertEquals(
            ['/api/v1/files/123', '/api/v1/files/456'],
            $request->getImages()
        );
        $this->assertEquals(
            ['/api/v1/alternate_identifiers/456', '/api/v1/alternate_identifiers/789'],
            $request->getAlternateIdentifiers()
        );
        $this->assertEquals(
            [
                '/api/v1/stores/123/properties/456/terms/123',
                '/api/v1/stores/123/properties/456/terms/456',
            ],
            $request->getPropertyTerms()
        );
        $this->assertEquals('/api/v1/stores/123', $request->getStore());
        $this->assertEquals('/api/v1/stores/123/products/456', $request->getIri());
    }

    public function testToArray(): void
    {
        $request = new ProductRequest(
            name: 'Product name',
            type: ProductType::Simple,
            price: '100.00',
            salePrice: '90.00',
            description: 'Product description',
            shortDescription: 'Product short description',
            weight: '100.00',
            length: '100.00',
            width: '100.00',
            height: '100.00',
            status: ProductStatus::Active,
            distributor: '/api/v1/stores/123/distributors/456',
            brand: '/api/v1/stores/123/brands/456',
            sku: '/api/v1/skus/123',
            image: '/api/v1/files/123',
            categories: [
                '/api/v1/stores/123/categories/456',
                '/api/v1/stores/123/categories/789',
            ],
            tags: [
                '/api/v1/stores/123/tags/456',
                '/api/v1/stores/123/tags/789',
            ],
            attributes: [
                '/api/v1/stores/123/attributes/456',
                '/api/v1/stores/123/attributes/789',
            ],
            images: [
                '/api/v1/files/123',
                '/api/v1/files/456',
            ],
            alternateIdentifiers: [
                '/api/v1/alternate_identifiers/456',
                '/api/v1/alternate_identifiers/789',
            ],
            propertyTerms: [
                '/api/v1/stores/123/properties/456/terms/123',
                '/api/v1/stores/123/properties/456/terms/456',
            ],
            store: '/api/v1/stores/123',
            iri: '/api/v1/stores/123/products/456'
        );

        $array = $request->toArray();
        $this->assertArrayHasKey('type', $array);
        $this->assertEquals(ProductType::Simple->value, $array['type']);

        $this->assertArrayHasKey('status', $array);
        $this->assertEquals(ProductStatus::Active->value, $array['status']);

        $this->assertArrayHasKey('sku', $array);
        $this->assertEquals('/api/v1/skus/123', $array['sku']);

        $this->assertArrayHasKey('name', $array);
        $this->assertEquals('Product name', $array['name']);

        $this->assertArrayHasKey('price', $array);
        $this->assertEquals('100.00', $array['price']);

        $this->assertArrayHasKey('salePrice', $array);
        $this->assertEquals('90.00', $array['salePrice']);

        $this->assertArrayHasKey('description', $array);
        $this->assertEquals('Product description', $array['description']);

        $this->assertArrayHasKey('shortDescription', $array);
        $this->assertEquals('Product short description', $array['shortDescription']);

        $this->assertArrayHasKey('weight', $array);
        $this->assertEquals('100.00', $array['weight']);

        $this->assertArrayHasKey('length', $array);
        $this->assertEquals('100.00', $array['length']);

        $this->assertArrayHasKey('width', $array);
        $this->assertEquals('100.00', $array['width']);

        $this->assertArrayHasKey('height', $array);
        $this->assertEquals('100.00', $array['height']);

        $this->assertArrayHasKey('distributor', $array);
        $this->assertEquals('/api/v1/stores/123/distributors/456', $array['distributor']);

        $this->assertArrayHasKey('brand', $array);
        $this->assertEquals('/api/v1/stores/123/brands/456', $array['brand']);

        $this->assertArrayHasKey('image', $array);
        $this->assertEquals('/api/v1/files/123', $array['image']);

        $this->assertArrayHasKey('categories', $array);
        $this->assertEquals(
            ['/api/v1/stores/123/categories/456', '/api/v1/stores/123/categories/789'],
            $array['categories']
        );

        $this->assertArrayHasKey('tags', $array);
        $this->assertEquals(
            ['/api/v1/stores/123/tags/456', '/api/v1/stores/123/tags/789'],
            $array['tags']
        );

        $this->assertArrayHasKey('attributes', $array);
        $this->assertEquals(
            ['/api/v1/stores/123/attributes/456', '/api/v1/stores/123/attributes/789'],
            $array['attributes']
        );

        $this->assertArrayHasKey('images', $array);
        $this->assertEquals(
            ['/api/v1/files/123', '/api/v1/files/456'],
            $array['images']
        );

        $this->assertArrayHasKey('alternateIdentifiers', $array);
        $this->assertEquals(
            ['/api/v1/alternate_identifiers/456', '/api/v1/alternate_identifiers/789'],
            $array['alternateIdentifiers']
        );

        $this->assertArrayHasKey('propertyTerms', $array);
        $this->assertEquals(
            [
                '/api/v1/stores/123/properties/456/terms/123',
                '/api/v1/stores/123/properties/456/terms/456',
            ],
            $array['propertyTerms']
        );

        $this->assertArrayNotHasKey('store', $array);
        $this->assertArrayNotHasKey('iri', $array);
    }
}
