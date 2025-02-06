<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Response\Store;

use Apiera\Sdk\DTO\Response\Store\StoreResponse;
use Apiera\Sdk\Enum\LdType;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;
use Tests\Unit\DTO\Response\AbstractDTOResponse;

final class StoreResponseTest extends AbstractDTOResponse
{
    protected function getResponseClass(): string
    {
        return StoreResponse::class;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getResponseData(): array
    {
        return [
            'ldId' => '/api/v1/stores/123/products/123',
            'ldType' => LdType::Product,
            'uuid' => Uuid::fromString('bfd2639c-7793-426a-a413-ea262e582208'),
            'createdAt' => new DateTimeImmutable('2021-01-01 00:00:00'),
            'updatedAt' => new DateTimeImmutable('2021-01-01 00:00:00'),
            'name' => 'Store',
            'description' => 'Description',
            'image' => '/api/v1/files/123',
            'properties' => [
                '/api/v1/stores/123/properties/456',
            ],
            'propertyTerms' => [
                '/api/v1/stores/123/properties/456/terms/789',
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getNullableFields(): array
    {
        return [
            'description' => null,
            'image' => null,
        ];
    }

    protected function getExpectedLdType(): LdType
    {
        return LdType::Product;
    }
}
