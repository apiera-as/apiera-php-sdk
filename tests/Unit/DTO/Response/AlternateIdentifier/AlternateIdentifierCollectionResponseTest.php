<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Response\AlternateIdentifier;

use Apiera\Sdk\DTO\Response\AlternateIdentifier\AlternateIdentifierCollectionResponse;
use Apiera\Sdk\DTO\Response\AlternateIdentifier\AlternateIdentifierResponse;
use Apiera\Sdk\DTO\Response\PartialCollectionView;
use Apiera\Sdk\Enum\LdType;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;
use Tests\Unit\DTO\Response\AbstractDTOCollectionResponse;

final class AlternateIdentifierCollectionResponseTest extends AbstractDTOCollectionResponse
{
    protected function getCollectionClass(): string
    {
        return AlternateIdentifierCollectionResponse::class;
    }

    protected function getMemberClass(): string
    {
        return AlternateIdentifierResponse::class;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getCollectionData(): array
    {
        $alternateIdentifierResponse = new AlternateIdentifierResponse(
            ldId: '/api/v1/alternate_identifiers/123',
            ldType: LdType::AlternateIdentifier,
            uuid: Uuid::fromString('bfd2639c-7793-426a-a413-ea262e582208'),
            createdAt: new DateTimeImmutable('2021-01-01 00:00:00'),
            updatedAt: new DateTimeImmutable('2021-01-01 00:00:00'),
            type: 'gtin',
            code: 'ABC123',
        );

        return [
            'ldContext' => '/api/v1/contexts/AlternateIdentifier',
            'ldId' => '/api/v1/alternate_identifiers',
            'ldType' => LdType::Collection,
            'ldMembers' => [$alternateIdentifierResponse],
            'ldTotalItems' => 1,
            'ldView' => new PartialCollectionView(
                ldId: '/api/v1/alternate_identifiers?page=1',
                ldFirst: '/api/v1/alternate_identifiers?page=1',
                ldLast: '/api/v1/alternate_identifiers?page=1',
                ldNext: null,
                ldPrevious: null
            ),
        ];
    }
}
