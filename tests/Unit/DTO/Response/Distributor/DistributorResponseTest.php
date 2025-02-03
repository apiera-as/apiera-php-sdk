<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Response\Distributor;

use Apiera\Sdk\DTO\Response\Distributor\DistributorResponse;
use Apiera\Sdk\Enum\LdType;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;
use Tests\Unit\DTO\Response\AbstractDTOResponse;

final class DistributorResponseTest extends AbstractDTOResponse
{
    protected function getResponseClass(): string
    {
        return DistributorResponse::class;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getResponseData(): array
    {
        return [
            'ldId' => '/api/v1/stores/123/attributes/123',
            'ldType' => LdType::Distributor,
            'uuid' => Uuid::fromString('bfd2639c-7793-426a-a413-ea262e582208'),
            'createdAt' => new DateTimeImmutable('2021-01-01 00:00:00'),
            'updatedAt' => new DateTimeImmutable('2021-01-01 00:00:00'),
            'name' => 'Distributor',
            'store' => '/api/v1/stores/123',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getNullableFields(): array
    {
        return [];
    }

    protected function getExpectedLdType(): LdType
    {
        return LdType::Distributor;
    }
}
