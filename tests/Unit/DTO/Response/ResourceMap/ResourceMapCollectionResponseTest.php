<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Response\ResourceMap;

use Apiera\Sdk\DTO\Response\PartialCollectionView;
use Apiera\Sdk\DTO\Response\ResourceMap\ResourceMapCollectionResponse;
use Apiera\Sdk\DTO\Response\ResourceMap\ResourceMapResponse;
use Apiera\Sdk\Enum\LdType;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;
use Tests\Unit\DTO\Response\AbstractDTOCollectionResponse;

final class ResourceMapCollectionResponseTest extends AbstractDTOCollectionResponse
{
    protected function getCollectionClass(): string
    {
        return ResourceMapCollectionResponse::class;
    }

    protected function getMemberClass(): string
    {
        return ResourceMapResponse::class;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getCollectionData(): array
    {
        $resourceMapResponse = new ResourceMapResponse(
            ldId: '/api/v1/integrations/123/mappings/123',
            ldType: LdType::IntegrationResourceMap,
            uuid: Uuid::fromString('bfd2639c-7793-426a-a413-ea262e582208'),
            createdAt: new DateTimeImmutable('2021-01-01 00:00:00'),
            updatedAt: new DateTimeImmutable('2021-01-01 00:00:00'),
            external: 'string',
            internal: 'string',
            resource: 'string',
            resourceType: 'string',
            integration: '/api/v1/integrations/123',
        );

        return [
            'ldContext' => '/api/v1/contexts/IntegrationResourceMap',
            'ldId' => '/api/v1/integrations/123/mappings',
            'ldType' => LdType::Collection,
            'ldMembers' => [$resourceMapResponse],
            'ldTotalItems' => 1,
            'ldView' => new PartialCollectionView(
                ldId: '/api/v1/integrations/123/mappings?page=1',
                ldFirst: '/api/v1/integrations/123/mappings?page=1',
                ldLast: '/api/v1/integrations/123/mappings?page=1',
                ldNext: null,
                ldPrevious: null
            ),
        ];
    }
}
