<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Response\Variant;

use Apiera\Sdk\DTO\Response\Variant\VariantResponse;
use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Enum\VariantStatus;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;
use Tests\Unit\DTO\Response\AbstractDTOResponse;

final class VariantResponseTest extends AbstractDTOResponse
{
    protected function getResponseClass(): string
    {
        return VariantResponse::class;
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
            'status' => VariantStatus::Active,
            'store' => '/api/v1/stores/123',
            'product' => '/api/v1/stores/123/products/123',
            'sku' => '/api/v1/skus/123',
            'price' => '100.00',
            'salePrice' => '99.00',
            'description' => 'Variant description',
            'weight' => '100.00',
            'length' => '100.00',
            'width' => '100.00',
            'height' => '100.00',
            'attributeTerms' => [
                '/api/v1/stores/123/attributes/123/terms/456',
            ],
            'images' => [
                '/api/v1/files/123',
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getNullableFields(): array
    {
        return [
            'price' => null,
            'salePrice' => null,
            'description' => null,
            'weight' => null,
            'length' => null,
            'width' => null,
            'height' => null,
        ];
    }

    protected function getExpectedLdType(): LdType
    {
        return LdType::Product;
    }
}
