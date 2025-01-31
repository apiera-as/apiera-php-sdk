<?php

declare(strict_types=1);

namespace Tests\Unit\DataMapper;

use Apiera\Sdk\DataMapper\InventoryDataMapper;
use Apiera\Sdk\DTO\Request\Inventory\InventoryRequest;
use Apiera\Sdk\DTO\Response\Inventory\InventoryCollectionResponse;
use Apiera\Sdk\DTO\Response\Inventory\InventoryResponse;
use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Exception\ClientException;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

final class InventoryDataMapperTest extends TestCase
{
    private InventoryDataMapper $mapper;

    /** @var array<string, mixed> */
    private array $sampleResponseData;

    /** @var array<string, mixed> */
    private array $sampleCollectionData;
    private InventoryRequest $sampleRequest;

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     */
    public function testFromResponseMapsAllFieldsCorrectly(): void
    {
        $result = $this->mapper->fromResponse($this->sampleResponseData);

        $this->assertInstanceOf(InventoryResponse::class, $result);
        $this->assertEquals('/api/v1/inventory_locations/123/inventories/456', $result->getLdId());
        $this->assertEquals(LdType::Inventory, $result->getLdType());
        $this->assertEquals(Uuid::fromString('123e4567-e89b-12d3-a456-426614174000'), $result->getUuid());
        $this->assertInstanceOf(DateTimeImmutable::class, $result->getCreatedAt());
        $this->assertInstanceOf(DateTimeImmutable::class, $result->getUpdatedAt());
        $this->assertEquals(1, $result->getQuantity());
        $this->assertEquals('/api/v1/skus/123', $result->getSku());
        $this->assertEquals('/api/v1/inventory_locations/123', $result->getInventoryLocation());
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     */
    public function testFromResponseThrowsExceptionForInvalidDate(): void
    {
        $data = $this->sampleResponseData;
        $data['createdAt'] = 'invalid-date';

        $this->expectException(ClientException::class);
        $this->mapper->fromResponse($data);
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     */
    public function testFromCollectionResponseThrowsExceptionForInvalidType(): void
    {
        $data = $this->sampleCollectionData;
        $data['@type'] = 'InvalidType';

        $this->expectException(ClientException::class);
        $this->expectExceptionMessage('Invalid collection type');
        $this->mapper->fromCollectionResponse($data);
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     */
    public function testFromCollectionResponseMapsDataCorrectly(): void
    {
        $result = $this->mapper->fromCollectionResponse($this->sampleCollectionData);

        $this->assertInstanceOf(InventoryCollectionResponse::class, $result);
        $this->assertEquals('/api/contexts/Inventory', $result->getLdContext());
        $this->assertEquals('/api/v1/inventory_locations/123/inventories', $result->getLdId());
        $this->assertEquals(LdType::Collection, $result->getLdType());
        $this->assertEquals(1, $result->getTotalItems());
        $this->assertCount(1, $result->getMembers());
        $this->assertInstanceOf(InventoryResponse::class, $result->getMembers()[0]);
        $this->assertEquals('/api/v1/inventory_locations/123/inventories?page=1', $result->getView());
        $this->assertEquals('/api/v1/inventory_locations/123/inventories?page=1', $result->getFirstPage());
        $this->assertEquals('/api/v1/inventory_locations/123/inventories?page=1', $result->getLastPage());
        $this->assertNull($result->getNextPage());
        $this->assertNull($result->getPreviousPage());
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     */
    public function testFromCollectionResponseHandlesEmptyCollection(): void
    {
        $data = $this->sampleCollectionData;
        $data['member'] = [];
        $data['totalItems'] = 0;

        $result = $this->mapper->fromCollectionResponse($data);

        $this->assertEmpty($result->getMembers());
        $this->assertEquals(0, $result->getTotalItems());
    }

    public function testToRequestDataIncludesRequiredFields(): void
    {
        $result = $this->mapper->toRequestData($this->sampleRequest);

        $expected = [
            'quantity' => 1,
            'sku' => '/api/v1/skus/123',
        ];

        $this->assertEquals($expected, $result);
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     */
    public function testFromCollectionResponseWithInvalidMemberThrowsException(): void
    {
        $data = $this->sampleCollectionData;
        $data['member'][0]['createdAt'] = 'invalid-date';

        $this->expectException(ClientException::class);
        $this->mapper->fromCollectionResponse($data);
    }

    protected function setUp(): void
    {
        $this->mapper = new InventoryDataMapper();

        $this->sampleResponseData = [
            '@id' => '/api/v1/inventory_locations/123/inventories/456',
            '@type' => 'Inventory',
            'uuid' => '123e4567-e89b-12d3-a456-426614174000',
            'createdAt' => '2025-01-01T00:00:00+00:00',
            'updatedAt' => '2025-01-01T00:00:00+00:00',
            'quantity' => 1,
            'sku' => '/api/v1/skus/123',
            'inventoryLocation' => '/api/v1/inventory_locations/123',
        ];

        $this->sampleCollectionData = [
            '@context' => '/api/contexts/Inventory',
            '@id' => '/api/v1/inventory_locations/123/inventories',
            '@type' => 'Collection',
            'member' => [$this->sampleResponseData],
            'totalItems' => 1,
            'view' => '/api/v1/inventory_locations/123/inventories?page=1',
            'firstPage' => '/api/v1/inventory_locations/123/inventories?page=1',
            'lastPage' => '/api/v1/inventory_locations/123/inventories?page=1',
            'nextPage' => null,
            'previousPage' => null,
        ];

        $this->sampleRequest = new InventoryRequest(
            quantity: 1,
            sku: '/api/v1/skus/123'
        );
    }
}
