<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Response\Organization;

use Apiera\Sdk\DTO\Response\Organization\OrganizationResponse;
use Apiera\Sdk\Enum\LdType;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;
use Tests\Unit\DTO\Response\AbstractDTOResponse;

final class OrganizationResponseTest extends AbstractDTOResponse
{
    protected function getResponseClass(): string
    {
        return OrganizationResponse::class;
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
            'name' => 'Product',
            'extId' => 'string',
            'alternateIdentifiers' => [
                '/api/v1/alternate_identifiers/345',
            ],
        ];
    }

    protected function getExpectedLdType(): LdType
    {
        return LdType::Product;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getNullableFields(): array
    {
        return [];
    }
}
