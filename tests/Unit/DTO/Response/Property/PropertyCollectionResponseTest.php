<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Response\Property;

use Apiera\Sdk\DTO\Response\PartialCollectionView;
use Apiera\Sdk\DTO\Response\Property\PropertyCollectionResponse;
use Apiera\Sdk\DTO\Response\Property\PropertyResponse;
use Apiera\Sdk\Enum\LdType;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;
use Tests\Unit\DTO\Response\AbstractDTOCollectionResponse;

final class PropertyCollectionResponseTest extends AbstractDTOCollectionResponse
{
    protected function getCollectionClass(): string
    {
        return PropertyCollectionResponse::class;
    }

    protected function getMemberClass(): string
    {
        return PropertyResponse::class;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getCollectionData(): array
    {
        $propertyResponse = new PropertyResponse(
            ldId: '/api/v1/stores/123/properties/123',
            ldType: LdType::Property,
            uuid: Uuid::fromString('bfd2639c-7793-426a-a413-ea262e582208'),
            createdAt: new DateTimeImmutable('2021-01-01 00:00:00'),
            updatedAt: new DateTimeImmutable('2021-01-01 00:00:00'),
            name: 'Material',
            store: '/api/v1/stores/123',
        );

        return [
            'ldContext' => '/api/v1/contexts/Property',
            'ldId' => '/api/v1/stores/123/properties',
            'ldType' => LdType::Collection,
            'ldMembers' => [$propertyResponse],
            'ldTotalItems' => 1,
            'ldView' => new PartialCollectionView(
                ldId: '/api/v1/stores/123/properties?page=1',
                ldFirst: '/api/v1/stores/123/properties?page=1',
                ldLast: '/api/v1/stores/123/properties?page=1',
                ldNext: null,
                ldPrevious: null
            ),
        ];
    }
}
