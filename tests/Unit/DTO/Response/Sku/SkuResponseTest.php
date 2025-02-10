<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Response\Sku;

use Apiera\Sdk\DTO\Response\Sku\SkuResponse;
use Apiera\Sdk\Enum\LdType;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;
use Tests\Unit\DTO\Response\AbstractDTOResponse;

final class SkuResponseTest extends AbstractDTOResponse
{
    protected function getResponseClass(): string
    {
        return SkuResponse::class;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getResponseData(): array
    {
        return [
            'ldId' => '/api/v1/skus/123',
            'ldType' => LdType::Sku,
            'uuid' => Uuid::fromString('bfd2639c-7793-426a-a413-ea262e582208'),
            'createdAt' => new DateTimeImmutable('2021-01-01 00:00:00'),
            'updatedAt' => new DateTimeImmutable('2021-01-01 00:00:00'),
            'code' => 'string',
            'products' => [
                '/api/v1/stores/123/products/123',
            ],
            'variants' => [
                '/api/v1/stores/123/products/123/variants/123',
            ],
            'inventories' => [
                '/api/v1/inventory_locations/123/inventories/123',
            ],
        ];
    }

    protected function getExpectedLdType(): LdType
    {
        return LdType::Sku;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getNullableFields(): array
    {
        return [];
    }
}
