<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Response\Sku;

use Apiera\Sdk\DTO\Response\PartialCollectionView;
use Apiera\Sdk\DTO\Response\Sku\SkuCollectionResponse;
use Apiera\Sdk\DTO\Response\Sku\SkuResponse;
use Apiera\Sdk\Enum\LdType;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;
use Tests\Unit\DTO\Response\AbstractDTOCollectionResponse;

final class SkuCollectionResponseTest extends AbstractDTOCollectionResponse
{
    protected function getCollectionClass(): string
    {
        return SkuCollectionResponse::class;
    }

    protected function getMemberClass(): string
    {
        return SkuResponse::class;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getCollectionData(): array
    {
        $skuResponse = new SkuResponse(
            ldId: '/api/v1/skus/123',
            ldType:LdType::Sku,
            uuid: Uuid::fromString('bfd2639c-7793-426a-a413-ea262e582208'),
            createdAt: new DateTimeImmutable('2021-01-01 00:00:00'),
            updatedAt: new DateTimeImmutable('2021-01-01 00:00:00'),
            code: 'string',
            products: [
                '/api/v1/stores/123/products/123',
            ],
            variants: [
                '/api/v1/stores/123/products/123/variants/123',
            ],
            inventories: [
                '/api/v1/inventory_locations/123/inventories/123',
            ],
        );

        return [
            'ldContext' => '/api/v1/contexts/Sku',
            'ldId' => '/api/v1/skus',
            'ldType' => LdType::Collection,
            'ldMembers' => [$skuResponse],
            'ldTotalItems' => 1,
            'ldView' => new PartialCollectionView(
                ldId: '/api/v1/skus?page=1',
                ldFirst: '/api/v1/skus?page=1',
                ldLast: '/api/v1/skus?page=1',
                ldNext: null,
                ldPrevious: null
            ),
        ];
    }
}
