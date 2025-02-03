<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Response\Brand;

use Apiera\Sdk\DTO\Response\Brand\BrandResponse;
use Apiera\Sdk\Enum\LdType;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;
use Tests\Unit\DTO\Response\AbstractDTOResponse;

final class BrandResponseTest extends AbstractDTOResponse
{
    protected function getResponseClass(): string
    {
        return BrandResponse::class;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getResponseData(): array
    {
        return [
            'ldId' => '/api/v1/stores/123/brands/123',
            'ldType' => LdType::Brand,
            'uuid' => Uuid::fromString('bfd2639c-7793-426a-a413-ea262e582208'),
            'createdAt' => new DateTimeImmutable('2021-01-01 00:00:00'),
            'updatedAt' => new DateTimeImmutable('2021-01-01 00:00:00'),
            'name' => 'Brand',
            'store' => '/api/v1/stores/123',
            'description' => 'Brand description',
            'image' => '/api/v1/files/123',
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
        return LdType::Brand;
    }
}
