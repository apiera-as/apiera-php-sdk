<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Response\Product;

use Apiera\Sdk\DTO\Response\Product\ProductResponse;
use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Enum\ProductStatus;
use Apiera\Sdk\Enum\ProductType;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;
use Tests\Unit\DTO\Response\AbstractDTOResponse;

final class ProductResponseTest extends AbstractDTOResponse
{
    protected function getResponseClass(): string
    {
        return ProductResponse::class;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getResponseData(): array
    {
        return [
            'ldId' => '/api/v1/stores/123/products/123',
            'ldType' => LdType::Product,
            'uuid' => Uuid::fromString('bfd2639c-7793-426a-a413-ea262e582208'),
            'createdAt' => new DateTimeImmutable('2021-01-01 00:00:00'),
            'updatedAt' => new DateTimeImmutable('2021-01-01 00:00:00'),
            'type' => ProductType::Simple,
            'status' => ProductStatus::Active,
            'store' => '/api/v1/stores/123',
            'sku' => '/api/v1/skus/123',
            'name' => 'Product',
            'price' => '100.00',
            'salePrice' => '99.00',
            'description' => 'Product description',
            'shortDescription' => 'Product short description',
            'weight' => '100.00',
            'length' => '100.00',
            'width' => '100.00',
            'height' => '100.00',
            'distributor' => '/api/v1/stores/123/distributors/123',
            'brand' => '/api/v1/stores/123/brands/123',
            'image' => '/api/v1/files/123',
            'categories' => [
                '/api/v1/stores/123/categories/456',
            ],
            'tags' => [
                '/api/v1/stores/123/tags/789',
            ],
            'attributes' => [
                '/api/v1/stores/123/attributes/123',
            ],
            'images' => [
                '/api/v1/files/456',
            ],
            'alternateIdentifiers' => [
                '/api/v1/alternate_identifiers/345',
            ],
            'propertyTerms' => [
                '/api/v1/stores/123/properties/456/terms/789',
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getNullableFields(): array
    {
        return [
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
        ];
    }

    protected function getExpectedLdType(): LdType
    {
        return LdType::Product;
    }
}
