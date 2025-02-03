<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Response\AlternateIdentifier;

use Apiera\Sdk\DTO\Response\AlternateIdentifier\AlternateIdentifierResponse;
use Apiera\Sdk\Enum\LdType;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;
use Tests\Unit\DTO\Response\AbstractDTOResponse;

final class AlternateIdentifierResponseTest extends AbstractDTOResponse
{
    protected function getResponseClass(): string
    {
        return AlternateIdentifierResponse::class;
    }

    protected function getResponseData(): array
    {
        return [
            'ldId' => '/api/v1/alternate_identifiers/123',
            'ldType' => LdType::AlternateIdentifier,
            'uuid' => Uuid::fromString('bfd2639c-7793-426a-a413-ea262e582208'),
            'createdAt' => new DateTimeImmutable('2021-01-01 00:00:00'),
            'updatedAt' => new DateTimeImmutable('2021-01-01 00:00:00'),
            'type' => 'gtin',
            'code' => 'ABC123'
        ];
    }

    protected function getNullableFields(): array
    {
        return [];
    }

    protected function getExpectedLdType(): LdType
    {
        return LdType::AlternateIdentifier;
    }
}
