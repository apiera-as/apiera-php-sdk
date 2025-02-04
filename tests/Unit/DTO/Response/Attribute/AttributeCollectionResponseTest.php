<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Response\Attribute;

use Apiera\Sdk\DTO\Response\Attribute\AttributeCollectionResponse;
use Apiera\Sdk\DTO\Response\Attribute\AttributeResponse;
use Apiera\Sdk\DTO\Response\PartialCollectionView;
use Apiera\Sdk\Enum\LdType;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;
use Tests\Unit\DTO\Response\AbstractDTOCollectionResponse;

final class AttributeCollectionResponseTest extends AbstractDTOCollectionResponse
{
    protected function getCollectionClass(): string
    {
        return AttributeCollectionResponse::class;
    }

    protected function getMemberClass(): string
    {
        return AttributeResponse::class;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getCollectionData(): array
    {
        $attributeResponse = new AttributeResponse(
            ldId: '/api/v1/stores/123/attributes/123',
            ldType: LdType::Attribute,
            uuid: Uuid::fromString('bfd2639c-7793-426a-a413-ea262e582208'),
            createdAt: new DateTimeImmutable('2021-01-01 00:00:00'),
            updatedAt: new DateTimeImmutable('2021-01-01 00:00:00'),
            name: 'Color',
            store: '/api/v1/stores/123',
        );

        return [
            'ldContext' => '/api/v1/contexts/AlternateIdentifier',
            'ldId' => '/api/v1/stores/123/attributes',
            'ldType' => LdType::Collection,
            'ldMembers' => [$attributeResponse],
            'ldTotalItems' => 1,
            'ldView' => new PartialCollectionView(
                ldId: '/api/v1/stores/123/attributes?page=1',
                ldFirst: '/api/v1/stores/123/attributes?page=1',
                ldLast: '/api/v1/stores/123/attributes?page=1',
                ldNext: null,
                ldPrevious: null
            ),
        ];
    }
}
