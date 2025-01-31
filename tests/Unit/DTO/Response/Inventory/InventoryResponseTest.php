<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Response\Inventory;

use Apiera\Sdk\DTO\Response\AbstractResponse;
use Apiera\Sdk\DTO\Response\Inventory\InventoryResponse;
use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Interface\DTO\JsonLDInterface;
use Apiera\Sdk\Interface\DTO\ResponseInterface;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Symfony\Component\Uid\Uuid;

final class InventoryResponseTest extends TestCase
{
    public function testInstanceOf(): void
    {
        $response = new InventoryResponse(
            id: '',
            type: LdType::Inventory,
            uuid: Uuid::v4(),
            createdAt: new DateTimeImmutable(),
            updatedAt: new DateTimeImmutable(),
            quantity: 0,
            sku: ''
        );

        $this->assertInstanceOf(InventoryResponse::class, $response);
        $this->assertInstanceOf(AbstractResponse::class, $response);
        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertInstanceOf(JsonLDInterface::class, $response);
    }

    public function testClassIsCorrectlyDefined(): void
    {
        $reflection = new ReflectionClass(InventoryResponse::class);

        $this->assertTrue($reflection->isReadonly(), 'Class should be readonly');
    }

    public function testPropertiesAreReadonly(): void
    {
        $reflection = new ReflectionClass(InventoryResponse::class);
        $properties = $reflection->getProperties();

        foreach ($properties as $property) {
            $this->assertTrue($property->isReadonly(), sprintf('Property %s should be readonly', $property->getName()));
        }
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

        $this->assertEquals('/api/v1/inventory_locations/123/inventories/456', $response->getLdId());
        $this->assertEquals(LdType::Inventory, $response->getLdType());
        $this->assertTrue(Uuid::isValid($response->getUuid()->toRfc4122()));
        $this->assertEquals('bfd2639c-7793-426a-a413-ea262e582208', $response->getUuid()->toRfc4122());
        $this->assertEquals(new DateTimeImmutable(), $response->getCreatedAt());
        $this->assertEquals(new DateTimeImmutable(), $response->getUpdatedAt());
        $this->assertEquals(3, $response->getQuantity());
        $this->assertEquals('/api/v1/skus/123', $response->getSku());
        $this->assertEquals('/api/v1/inventory_locations/123', $response->getInventoryLocation());
    }
}
