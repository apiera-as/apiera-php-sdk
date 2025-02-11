<?php

declare(strict_types=1);

namespace Tests\Integration\Resource;

trait InventoryLocationScopedTrait
{
    use ResourceOperationTrait;

    protected string $inventoryLocationId = '9d024f41-3faf-4eef-9d2d-a7e506b81afb';

    abstract protected function getInventoryLocationScopedResourcePath(): string;

    protected function getResourcePath(): string
    {
        return $this->normalizePath(
            'inventory_locations',
            $this->inventoryLocationId,
            $this->getInventoryLocationScopedResourcePath()
        );
    }

    protected function buildIntegrationUri(string ...$segments): string
    {
        return $this->buildUri('inventory_locations', $this->inventoryLocationId, ...$segments);
    }
}
