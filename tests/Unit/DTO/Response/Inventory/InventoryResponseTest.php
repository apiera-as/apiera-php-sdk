<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Response\Inventory;

use Apiera\Sdk\DTO\Response\Inventory\InventoryResponse;
use Apiera\Sdk\Enum\LdType;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;
use Tests\Unit\DTO\Response\AbstractDTOResponse;

final class InventoryResponseTest extends AbstractDTOResponse
{
    protected function getResponseClass(): string
    {
        return InventoryResponse::class;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getResponseData(): array
    {
        return [
            'ldId' => '/api/v1/inventory_locations/123/inventories/123',
            'ldType' => LdType::Inventory,
            'uuid' => Uuid::fromString('bfd2639c-7793-426a-a413-ea262e582208'),
            'createdAt' => new DateTimeImmutable('2021-01-01 00:00:00'),
            'updatedAt' => new DateTimeImmutable('2021-01-01 00:00:00'),
            'quantity' => 0,
            'sku' => '/api/v1/skus/123',
            'inventoryLocation' => '/api/v1/inventory_locations/123',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getNullableFields(): array
    {
        return [];
    }

    protected function getExpectedLdType(): LdType
    {
        return LdType::Inventory;
    }
}
