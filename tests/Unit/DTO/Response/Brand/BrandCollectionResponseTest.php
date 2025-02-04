<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Response\Brand;

use Apiera\Sdk\DTO\Response\Brand\BrandCollectionResponse;
use Apiera\Sdk\DTO\Response\Brand\BrandResponse;
use Apiera\Sdk\DTO\Response\PartialCollectionView;
use Apiera\Sdk\Enum\LdType;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;
use Tests\Unit\DTO\Response\AbstractDTOCollectionResponse;

final class BrandCollectionResponseTest extends AbstractDTOCollectionResponse
{
    protected function getCollectionClass(): string
    {
        return BrandCollectionResponse::class;
    }

    protected function getMemberClass(): string
    {
        return BrandResponse::class;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getCollectionData(): array
    {
        $brandResponse = new BrandResponse(
            ldId: '/api/v1/stores/123/brands/123',
            ldType: LdType::Brand,
            uuid: Uuid::fromString('bfd2639c-7793-426a-a413-ea262e582208'),
            createdAt: new DateTimeImmutable('2021-01-01 00:00:00'),
            updatedAt: new DateTimeImmutable('2021-01-01 00:00:00'),
            name: 'Brand',
            store: '/api/v1/stores/123',
            description: 'Brand description',
            image: '/api/v1/files/123',
        );

        return [
            'ldContext' => '/api/v1/contexts/Brand',
            'ldId' => '/api/v1/stores/123/brands',
            'ldType' => LdType::Collection,
            'ldMembers' => [$brandResponse],
            'ldTotalItems' => 1,
            'ldView' => new PartialCollectionView(
                ldId: '/api/v1/stores/123/brands?page=1',
                ldFirst: '/api/v1/stores/123/brands?page=1',
                ldLast: '/api/v1/stores/123/brands?page=1',
                ldNext: null,
                ldPrevious: null
            ),
        ];
    }
}
