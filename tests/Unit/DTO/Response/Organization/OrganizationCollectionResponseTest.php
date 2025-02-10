<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Response\Organization;

use Apiera\Sdk\DTO\Response\Organization\OrganizationCollectionResponse;
use Apiera\Sdk\DTO\Response\Organization\OrganizationResponse;
use Apiera\Sdk\DTO\Response\PartialCollectionView;
use Apiera\Sdk\Enum\LdType;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;
use Tests\Unit\DTO\Response\AbstractDTOCollectionResponse;

final class OrganizationCollectionResponseTest extends AbstractDTOCollectionResponse
{
    protected function getCollectionClass(): string
    {
        return OrganizationCollectionResponse::class;
    }

    protected function getMemberClass(): string
    {
        return OrganizationResponse::class;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getCollectionData(): array
    {
        $organizationResponse = new OrganizationResponse(
            ldId: '/api/v1/organizations/123',
            ldType: LdType::Organization,
            uuid: Uuid::fromString('bfd2639c-7793-426a-a413-ea262e582208'),
            createdAt: new DateTimeImmutable('2021-01-01 00:00:00'),
            updatedAt: new DateTimeImmutable('2021-01-01 00:00:00'),
            name: 'string',
            extId: 'string',
        );

        return [
            'ldContext' => '/api/v1/contexts/Organization',
            'ldId' => '/api/v1/organizations',
            'ldType' => LdType::Collection,
            'ldMembers' => [$organizationResponse],
            'ldTotalItems' => 1,
            'ldView' => new PartialCollectionView(
                ldId: '/api/v1/organizations?page=1',
                ldFirst: '/api/v1/organizations?page=1',
                ldLast: '/api/v1/organizations?page=1',
                ldNext: null,
                ldPrevious: null
            ),
        ];
    }
}
