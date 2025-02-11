<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Response\Inventory;

use Apiera\Sdk\DTO\Response\Inventory\InventoryCollectionResponse;
use Apiera\Sdk\DTO\Response\Inventory\InventoryResponse;
use Apiera\Sdk\DTO\Response\PartialCollectionView;
use Apiera\Sdk\Enum\LdType;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;
use Tests\Unit\DTO\Response\AbstractDTOCollectionResponse;

final class InventoryCollectionResponseTest extends AbstractDTOCollectionResponse
{
    protected function getCollectionClass(): string
    {
        return InventoryCollectionResponse::class;
    }

    protected function getMemberClass(): string
    {
        return InventoryResponse::class;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getCollectionData(): array
    {
        $inventoryResponse = new InventoryResponse(
            ldId: '/api/v1/inventory_locations/123/inventories/123',
            ldType: LdType::Inventory,
            uuid: Uuid::fromString('bfd2639c-7793-426a-a413-ea262e582208'),
            createdAt: new DateTimeImmutable('2021-01-01 00:00:00'),
            updatedAt: new DateTimeImmutable('2021-01-01 00:00:00'),
            quantity: 0,
            sku: '/api/v1/skus/123',
            inventoryLocation: '/api/v1/inventory_locations/123',
        );

        return [
            'ldContext' => '/api/v1/contexts/Inventory',
            'ldId' => '/api/v1/inventory_locations/123/inventories',
            'ldType' => LdType::Collection,
            'ldMembers' => [$inventoryResponse],
            'ldTotalItems' => 1,
            'ldView' => new PartialCollectionView(
                ldId: '/api/v1/inventory_locations/123/inventories?page=1',
                ldFirst: '/api/v1/inventory_locations/123/inventories?page=1',
                ldLast: '/api/v1/inventory_locations/123/inventories?page=1',
                ldNext: null,
                ldPrevious: null
            ),
        ];
    }
}
