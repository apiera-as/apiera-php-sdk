<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Response\PropertyTerm;

use Apiera\Sdk\DTO\Response\PartialCollectionView;
use Apiera\Sdk\DTO\Response\PropertyTerm\PropertyTermResponse;
use Apiera\Sdk\Enum\LdType;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;
use Tests\Unit\DTO\Response\AbstractDTOCollectionResponse;

final class PropertyTermCollectionResponse extends AbstractDTOCollectionResponse
{
    protected function getCollectionClass(): string
    {
        return PropertyTermCollectionResponse::class;
    }

    protected function getMemberClass(): string
    {
        return PropertyTermResponse::class;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getCollectionData(): array
    {
        $propertyTermResponse = new PropertyTermResponse(
            ldId: '/api/v1/stores/123/properties/123/terms/123',
            ldType: LdType::PropertyTerm,
            uuid: Uuid::fromString('bfd2639c-7793-426a-a413-ea262e582208'),
            createdAt: new DateTimeImmutable('2021-01-01 00:00:00'),
            updatedAt: new DateTimeImmutable('2021-01-01 00:00:00'),
            name: 'PropertyTerm',
            property: 'string',
        );

        return [
            'ldContext' => '/api/v1/contexts/PropertyTerm',
            'ldId' => '/api/v1/stores/123/properties/123/terms',
            'ldType' => LdType::Collection,
            'ldMembers' => [$propertyTermResponse],
            'ldTotalItems' => 1,
            'ldView' => new PartialCollectionView(
                ldId: '/api/v1/stores/123/properties/123/terms?page=1',
                ldFirst: '/api/v1/stores/123/properties/123/terms?page=1',
                ldLast: '/api/v1/stores/123/properties/123/terms?page=1',
                ldNext: null,
                ldPrevious: null
            ),
        ];
    }
}
