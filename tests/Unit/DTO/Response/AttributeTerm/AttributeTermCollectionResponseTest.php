<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Response\AttributeTerm;

use Apiera\Sdk\DTO\Response\AttributeTerm\AttributeTermCollectionResponse;
use Apiera\Sdk\DTO\Response\AttributeTerm\AttributeTermResponse;
use Apiera\Sdk\DTO\Response\PartialCollectionView;
use Apiera\Sdk\Enum\LdType;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;
use Tests\Unit\DTO\Response\AbstractDTOCollectionResponse;

final class AttributeTermCollectionResponseTest extends AbstractDTOCollectionResponse
{
    protected function getCollectionClass(): string
    {
        return AttributeTermCollectionResponse::class;
    }

    protected function getMemberClass(): string
    {
        return AttributeTermResponse::class;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getCollectionData(): array
    {
        $attributeTermResponse = new AttributeTermResponse(
            ldId: '/api/v1/stores/123/attributes/123/terms/123',
            ldType: LdType::AttributeTerm,
            uuid: Uuid::fromString('bfd2639c-7793-426a-a413-ea262e582208'),
            createdAt: new DateTimeImmutable('2021-01-01 00:00:00'),
            updatedAt: new DateTimeImmutable('2021-01-01 00:00:00'),
            name: 'Green',
            attribute: '/api/v1/stores/123/attributes/123',
            store: '/api/v1/stores/123',
        );

        return [
            'ldContext' => '/api/v1/contexts/AlternateIdentifier',
            'ldId' => '/api/v1/stores/123/attributes/123/terms',
            'ldType' => LdType::Collection,
            'ldMembers' => [$attributeTermResponse],
            'ldTotalItems' => 1,
            'ldView' => new PartialCollectionView(
                ldId: '/api/v1/stores/123/attributes/123/terms?page=1',
                ldFirst: '/api/v1/stores/123/attributes/123/terms?page=1',
                ldLast: '/api/v1/stores/123/attributes/123/terms?page=1',
                ldNext: null,
                ldPrevious: null
            ),
        ];
    }
}
