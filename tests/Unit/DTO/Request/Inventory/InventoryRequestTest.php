<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Request\Inventory;

use Apiera\Sdk\DTO\Request\Inventory\InventoryRequest;
use Apiera\Sdk\Interface\DTO\RequestInterface;
use PHPUnit\Framework\TestCase;

final class InventoryRequestTest extends TestCase
{
    public function testInstanceOf(): void
    {
        $request = new InventoryRequest(0, '');

        $this->assertInstanceOf(InventoryRequest::class, $request);
        $this->assertInstanceOf(RequestInterface::class, $request);
    }

    public function testConstructorAndGetters(): void
    {
        $request = new InventoryRequest(
            quantity: 1,
            sku: '/api/v1/skus/123',
            inventoryLocation: '/api/v1/inventory_locations/123',
            iri: '/api/v1/inventory_locations/123/inventories/456'
        );

        $this->assertEquals(1, $request->getQuantity());
        $this->assertEquals('/api/v1/skus/123', $request->getSku());
        $this->assertEquals('/api/v1/inventory_locations/123', $request->getInventoryLocation());
        $this->assertEquals('/api/v1/inventory_locations/123/inventories/456', $request->getIri());
    }

    public function testToArray(): void
    {
        $request = new InventoryRequest(
            quantity: 1,
            sku: '/api/v1/skus/123',
            inventoryLocation: '/api/v1/inventory_locations/123',
            iri: '/api/v1/inventory_locations/123/inventories/456'
        );

        $array = $request->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('quantity', $array);
        $this->assertArrayHasKey('sku', $array);
        $this->assertArrayNotHasKey('inventory_location', $array);
        $this->assertArrayNotHasKey('iri', $array);

        $this->assertEquals([
            'quantity' => 1,
            'sku' => '/api/v1/skus/123',
        ], $array);
    }
}
