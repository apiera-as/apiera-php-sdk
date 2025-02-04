<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Response\InventoryLocation;

use Apiera\Sdk\DTO\Response\InventoryLocation\InventoryLocationCollectionResponse;
use Apiera\Sdk\DTO\Response\InventoryLocation\InventoryLocationResponse;
use Apiera\Sdk\DTO\Response\PartialCollectionView;
use Apiera\Sdk\Enum\LdType;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;
use Tests\Unit\DTO\Response\AbstractDTOCollectionResponse;

final class InventoryLocationCollectionResponseTest extends AbstractDTOCollectionResponse
{
    protected function getCollectionClass(): string
    {
        return InventoryLocationCollectionResponse::class;
    }

    protected function getMemberClass(): string
    {
        return InventoryLocationResponse::class;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getCollectionData(): array
    {
        $inventoryLocationResponse = new InventoryLocationResponse(
            ldId: '/api/v1/inventory_locations/123',
            ldType: LdType::InventoryLocation,
            uuid: Uuid::fromString('bfd2639c-7793-426a-a413-ea262e582208'),
            createdAt: new DateTimeImmutable('2021-01-01 00:00:00'),
            updatedAt: new DateTimeImmutable('2021-01-01 00:00:00'),
            name: 'Example Warehouse',
            address1: '789 Placeholder Ave',
            city: 'Testville',
            state: 'TX',
            zipCode: '99999',
            country: 'US',
            address2: 'Building 5',
            phone: '555-123-4567',
            email: 'warehouse@test.com',
        );

        return [
            'ldContext' => '/api/v1/contexts/InventoryLocation',
            'ldId' => '/api/v1/inventory_locations',
            'ldType' => LdType::Collection,
            'ldMembers' => [$inventoryLocationResponse],
            'ldTotalItems' => 1,
            'ldView' => new PartialCollectionView(
                ldId: '/api/v1/inventory_locations?page=1',
                ldFirst: '/api/v1/inventory_locations?page=1',
                ldLast: '/api/v1/inventory_locations?page=1',
                ldNext: null,
                ldPrevious: null
            ),
        ];
    }
}
