<?php

declare(strict_types=1);

namespace Tests\Integration\Resource\Product;

use Apiera\Sdk\DTO\Request\Product\ProductRequest;
use Apiera\Sdk\DTO\Response\Product\ProductResponse;
use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Enum\ProductStatus;
use Apiera\Sdk\Enum\ProductType;
use Apiera\Sdk\Interface\DTO\ResponseInterface;
use Tests\Integration\Resource\AbstractTestGetOperation;
use Tests\Integration\Resource\StoreScopedOperationTrait;

final class GetProductTest extends AbstractTestGetOperation
{
    use StoreScopedOperationTrait;

    protected function getStoreScopedResourcePath(): string
    {
        return '/products';
    }

    protected function getResourceType(): string
    {
        return LdType::Product->value;
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    protected function executeGetOperation(): ProductResponse
    {
        $request = new ProductRequest(
            iri: $this->buildStoreUri('products', $this->resourceId)
        );

        return $this->sdk->product()->get($request);
    }

    /**
     * @return array<string, mixed>
     */
    protected function getMockResponseData(): array
    {
        return [
            '@id' => $this->buildStoreUri('products', $this->resourceId),
            '@type' => $this->getResourceType(),
            'uuid' => $this->resourceId,
            'createdAt' => self::CREATED_AT,
            'updatedAt' => self::UPDATED_AT,
            'type' => 'simple',
            'status' => 'active',
            'store' => $this->buildStoreUri(),
            'sku' => $this->buildUri('skus', $this->resourceId),
            'name' => 'Test product',
            'price' => '100.00',
            'salePrice' => '99.00',
            'description' => 'Test product description',
            'shortDescription' => 'Test product short description',
            'weight' => '100.00',
            'length' => '100.00',
            'width' => '100.00',
            'height' => '100.00',
            'distributor' => $this->buildStoreUri('distributors', $this->resourceId),
            'brand' => $this->buildStoreUri('brands', $this->resourceId),
            'image' => $this->buildUri('files', $this->resourceId),
            'categories' => [$this->buildStoreUri('categories', $this->resourceId)],
            'tags' => [$this->buildStoreUri('tags', $this->resourceId)],
            'attributes' => [$this->buildStoreUri('attributes', $this->resourceId)],
            'images' => [$this->buildUri('files', $this->resourceId)],
            'alternateIdentifiers' => [$this->buildUri('alternate_identifiers', $this->resourceId)],
            'propertyTerms' => [$this->buildStoreUri('properties', $this->resourceId, 'terms', $this->resourceId)],
        ];
    }

    /**
     * @return class-string<ProductResponse>
     */
    protected function getResponseClass(): string
    {
        return ProductResponse::class;
    }

    /**
     * @param ProductResponse $response
     */
    protected function assertResourceSpecificFields(ResponseInterface $response): void
    {
        $this->assertEquals(ProductType::Simple, $response->getType());
        $this->assertEquals(ProductStatus::Active, $response->getStatus());
        $this->assertEquals($this->buildStoreUri(), $response->getStore());
        $this->assertEquals($this->buildUri('skus', $this->resourceId), $response->getSku());
        $this->assertEquals('Test product', $response->getName());
        $this->assertEquals('100.00', $response->getPrice());
        $this->assertEquals('99.00', $response->getSalePrice());
        $this->assertEquals('Test product description', $response->getDescription());
        $this->assertEquals('Test product short description', $response->getShortDescription());
        $this->assertEquals('100.00', $response->getWeight());
        $this->assertEquals('100.00', $response->getLength());
        $this->assertEquals('100.00', $response->getWidth());
        $this->assertEquals('100.00', $response->getHeight());
        $this->assertEquals($this->buildStoreUri('distributors', $this->resourceId), $response->getDistributor());
        $this->assertEquals($this->buildStoreUri('brands', $this->resourceId), $response->getBrand());
        $this->assertEquals($this->buildUri('files', $this->resourceId), $response->getImage());
        $this->assertEquals([$this->buildStoreUri('categories', $this->resourceId)], $response->getCategories());
        $this->assertEquals([$this->buildStoreUri('tags', $this->resourceId)], $response->getTags());
        $this->assertEquals([$this->buildStoreUri('attributes', $this->resourceId)], $response->getAttributes());
        $this->assertEquals([$this->buildUri('files', $this->resourceId)], $response->getImages());
        $this->assertEquals(
            [$this->buildUri('alternate_identifiers', $this->resourceId)],
            $response->getAlternateIdentifiers()
        );
        $this->assertEquals(
            [$this->buildStoreUri('properties', $this->resourceId, 'terms', $this->resourceId)],
            $response->getPropertyTerms()
        );
    }
}
