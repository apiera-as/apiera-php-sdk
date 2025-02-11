<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Request\Inventory;

use Apiera\Sdk\DTO\Request\Inventory\InventoryRequest;
use Tests\Unit\DTO\Request\AbstractDTORequest;

final class InventoryRequestTest extends AbstractDTORequest
{
    protected function getRequestClass(): string
    {
        return InventoryRequest::class;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getConstructorParams(): array
    {
        return [
            'quantity' => 0,
            'sku' => '/api/v1/skus/123',
            'inventoryLocation' => '/api/v1/inventory_locations/123',
        ];
    }
}
