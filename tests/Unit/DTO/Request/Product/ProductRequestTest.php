<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Request\Product;

use Apiera\Sdk\DTO\Request\Product\ProductRequest;
use Apiera\Sdk\Enum\ProductStatus;
use Apiera\Sdk\Enum\ProductType;
use Tests\Unit\DTO\Request\AbstractDTORequest;

final class ProductRequestTest extends AbstractDTORequest
{
    protected function getRequestClass(): string
    {
        return ProductRequest::class;
    }

    protected function getConstructorParams(): array
    {
        return [
            'name' => 'Product',
            'type' => ProductType::Simple,
            'price' => '100.00',
            'salePrice' => '99.00',
            'description' => 'Description',
            'shortDescription' => 'Short description',
            'weight' => '100.00',
            'length' => '100.00',
            'width' => '100.00',
            'height' => '100.00',
            'status' => ProductStatus::Active,
            'distributor' => '/api/v1/stores/123/distributors/123',
            'brand' => '/api/v1/stores/123/brands/123',
            'sku' => '/api/v1/skus/123',
            'image' => '/api/v1/files/123',
            'categories' => [
                '/api/v1/stores/123/categories/456'
            ],
            'tags' => [
                '/api/v1/stores/123/tags/789'
            ],
            'attributes' => [
                '/api/v1/stores/123/attributes/123'
            ],
            'images' => [
                '/api/v1/files/456'
            ],
            'alternateIdentifiers' => [
                '/api/v1/alternate_identifiers/345'
            ],
            'propertyTerms' => [
                '/api/v1/stores/123/properties/456/terms/789'
            ]
        ];
    }
}
