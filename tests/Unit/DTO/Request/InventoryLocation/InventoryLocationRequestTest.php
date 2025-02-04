<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Request\InventoryLocation;

use Apiera\Sdk\DTO\Request\InventoryLocation\InventoryLocationRequest;
use Tests\Unit\DTO\Request\AbstractDTORequest;

final class InventoryLocationRequestTest extends AbstractDTORequest
{
    protected function getRequestClass(): string
    {
        return InventoryLocationRequest::class;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getConstructorParams(): array
    {
        return [
            'name' => 'Example Warehouse',
            'address1' => '789 Placeholder Ave',
            'address2' => 'Building 5',
            'city' => 'Testville',
            'state' => 'TX',
            'zipCode' => '99999',
            'country' => 'US',
            'phone' => '555-123-4567',
            'email' => 'warehouse@test.com',
            'iri' => '/api/v1/inventory_locations/999',
        ];
    }
}
