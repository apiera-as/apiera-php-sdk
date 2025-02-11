<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Response\Distributor;

use Apiera\Sdk\DTO\Response\Distributor\DistributorCollectionResponse;
use Apiera\Sdk\DTO\Response\Distributor\DistributorResponse;
use Apiera\Sdk\DTO\Response\PartialCollectionView;
use Apiera\Sdk\Enum\LdType;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;
use Tests\Unit\DTO\Response\AbstractDTOCollectionResponse;

final class DistributorCollectionResponseTest extends AbstractDTOCollectionResponse
{
    protected function getCollectionClass(): string
    {
        return DistributorCollectionResponse::class;
    }

    protected function getMemberClass(): string
    {
        return DistributorResponse::class;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getCollectionData(): array
    {
        $distributorResponse = new DistributorResponse(
            ldId: '/api/v1/stores/123/distributors/123',
            ldType: LdType::Distributor,
            uuid: Uuid::fromString('bfd2639c-7793-426a-a413-ea262e582208'),
            createdAt: new DateTimeImmutable('2021-01-01 00:00:00'),
            updatedAt: new DateTimeImmutable('2021-01-01 00:00:00'),
            name: 'Distributor',
            store: '/api/v1/stores/123',
        );

        return [
            'ldContext' => '/api/v1/contexts/Distributor',
            'ldId' => '/api/v1/stores/123/distributors',
            'ldType' => LdType::Collection,
            'ldMembers' => [$distributorResponse],
            'ldTotalItems' => 1,
            'ldView' => new PartialCollectionView(
                ldId: '/api/v1/stores/123/distributors?page=1',
                ldFirst: '/api/v1/stores/123/distributors?page=1',
                ldLast: '/api/v1/stores/123/distributors?page=1',
                ldNext: null,
                ldPrevious: null
            ),
        ];
    }
}
