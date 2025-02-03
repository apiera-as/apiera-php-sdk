<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Response\InventoryLocation;

use Apiera\Sdk\DTO\Response\InventoryLocation\InventoryLocationResponse;
use Apiera\Sdk\Enum\LdType;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;
use Tests\Unit\DTO\Response\AbstractDTOResponse;

final class InventoryLocationResponseTest extends AbstractDTOResponse
{
    protected function getResponseClass(): string
    {
        return InventoryLocationResponse::class;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getResponseData(): array
    {
        return [
            'ldId' => '/api/v1/inventory_locations/123',
            'ldType' => LdType::InventoryLocation,
            'uuid' => Uuid::fromString('bfd2639c-7793-426a-a413-ea262e582208'),
            'createdAt' => new DateTimeImmutable('2021-01-01 00:00:00'),
            'updatedAt' => new DateTimeImmutable('2021-01-01 00:00:00'),
            'name' => 'Example Warehouse',
            'address1' => '789 Placeholder Ave',
            'address2' => 'Building 5',
            'city' => 'Testville',
            'state' => 'TX',
            'zipCode' => '99999',
            'country' => 'US',
            'phone' => '555-123-4567',
            'email' => 'warehouse@test.com',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getNullableFields(): array
    {
        return [
            'address2' => null,
            'phone' => null,
            'email' => null,
        ];
    }

    protected function getExpectedLdType(): LdType
    {
        return LdType::InventoryLocation;
    }
}
