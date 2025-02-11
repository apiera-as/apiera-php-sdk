<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Response\Store;

use Apiera\Sdk\DTO\Response\PartialCollectionView;
use Apiera\Sdk\DTO\Response\Store\StoreCollectionResponse;
use Apiera\Sdk\DTO\Response\Store\StoreResponse;
use Apiera\Sdk\Enum\LdType;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;
use Tests\Unit\DTO\Response\AbstractDTOCollectionResponse;

final class StoreCollectionResponseTest extends AbstractDTOCollectionResponse
{
    protected function getCollectionClass(): string
    {
        return StoreCollectionResponse::class;
    }

    protected function getMemberClass(): string
    {
        return StoreResponse::class;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getCollectionData(): array
    {
        $storeResponse = new StoreResponse(
            ldId: '/api/v1/stores/123/store/123',
            ldType: LdType::Store,
            uuid: Uuid::fromString('bfd2639c-7793-426a-a413-ea262e582208'),
            createdAt: new DateTimeImmutable('2021-01-01 00:00:00'),
            updatedAt: new DateTimeImmutable('2021-01-01 00:00:00'),
            name: 'Store',
            description: 'Description',
            image: '/api/v1/files/123',
        );

        return [
            'ldContext' => '/api/v1/contexts/Store',
            'ldId' => '/api/v1/stores/123/stores',
            'ldType' => LdType::Collection,
            'ldMembers' => [$storeResponse],
            'ldTotalItems' => 1,
            'ldView' => new PartialCollectionView(
                ldId: '/api/v1/stores/123/products?page=1',
                ldFirst: '/api/v1/stores/123/products?page=1',
                ldLast: '/api/v1/stores/123/products?page=1',
                ldNext: null,
                ldPrevious: null
            ),
        ];
    }
}
