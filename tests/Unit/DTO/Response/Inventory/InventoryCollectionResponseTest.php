<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Response\Inventory;

use Apiera\Sdk\DTO\Response\AbstractCollectionResponse;
use Apiera\Sdk\DTO\Response\Inventory\InventoryCollectionResponse;
use Apiera\Sdk\DTO\Response\Inventory\InventoryResponse;
use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Interface\DTO\JsonLDCollectionInterface;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Symfony\Component\Uid\Uuid;

final class InventoryCollectionResponseTest extends TestCase
{
    public function testInstanceOf(): void
    {
        $response = new InventoryCollectionResponse(
            context: '',
            id: '',
            type: LdType::Collection,
            members: [],
            totalItems: 0
        );

        $this->assertInstanceOf(InventoryCollectionResponse::class, $response);
        $this->assertInstanceOf(AbstractCollectionResponse::class, $response);
        $this->assertInstanceOf(JsonLDCollectionInterface::class, $response);
    }

    public function testClassIsCorrectlyDefined(): void
    {
        $reflection = new ReflectionClass(InventoryCollectionResponse::class);

        $this->assertTrue($reflection->isReadonly(), 'Class should be readonly');
    }

    public function testConstructorAndGetters(): void
    {
        $response = new InventoryResponse(
            id: '/api/v1/inventory_locations/123/inventories/456',
            type: LdType::Inventory,
            uuid: Uuid::fromString('bfd2639c-7793-426a-a413-ea262e582208'),
            createdAt: new DateTimeImmutable(),
            updatedAt: new DateTimeImmutable(),
            quantity: 3,
            sku: '/api/v1/skus/123',
            inventoryLocation: '/api/v1/inventory_locations/123'
        );

        $response = new InventoryCollectionResponse(
            context: '/api/v1/contexts/Inventory',
            id: '/api/v1/inventory_locations/123/inventories',
            type: LdType::Collection,
            members: [$response],
            totalItems: 1,
            view: '/api/v1/inventory_locations/123/inventories?page=1',
            firstPage: '/api/v1/inventory_locations/123/inventories?page=1',
            lastPage: '/api/v1/inventory_locations/123/inventories?page=1',
            nextPage: null,
            previousPage: null
        );

        $this->assertEquals('/api/v1/contexts/Inventory', $response->getLdContext());
        $this->assertEquals('/api/v1/inventory_locations/123/inventories', $response->getLdId());
        $this->assertEquals(LdType::Collection, $response->getLdType());
        $this->assertCount(1, $response->getMembers());
        $this->assertInstanceOf(InventoryResponse::class, $response->getMembers()[0]);
        $this->assertEquals(1, $response->getTotalItems());
        $this->assertEquals('/api/v1/inventory_locations/123/inventories?page=1', $response->getView());
        $this->assertEquals('/api/v1/inventory_locations/123/inventories?page=1', $response->getFirstPage());
        $this->assertEquals('/api/v1/inventory_locations/123/inventories?page=1', $response->getLastPage());
        $this->assertNull($response->getNextPage());
        $this->assertNull($response->getPreviousPage());
    }

    public function testConstructorWithMinimalParameters(): void
    {
        $response = new InventoryCollectionResponse(
            context: '/api/v1/contexts/Inventory',
            id: '/api/v1/inventory_locations/123/inventories',
            type: LdType::Collection,
            members: [],
            totalItems: 0
        );

        $this->assertEquals('/api/v1/contexts/Inventory', $response->getLdContext());
        $this->assertEquals('/api/v1/inventory_locations/123/inventories', $response->getLdId());
        $this->assertEquals(LdType::Collection, $response->getLdType());
        $this->assertEmpty($response->getMembers());
        $this->assertEquals(0, $response->getTotalItems());
        $this->assertNull($response->getView());
        $this->assertNull($response->getFirstPage());
        $this->assertNull($response->getLastPage());
        $this->assertNull($response->getNextPage());
        $this->assertNull($response->getPreviousPage());
    }
}
