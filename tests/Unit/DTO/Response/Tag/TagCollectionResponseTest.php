<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Response\Tag;

use Apiera\Sdk\DTO\Response\PartialCollectionView;
use Apiera\Sdk\DTO\Response\Tag\TagCollectionResponse;
use Apiera\Sdk\DTO\Response\Tag\TagResponse;
use Apiera\Sdk\Enum\LdType;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;
use Tests\Unit\DTO\Response\AbstractDTOCollectionResponse;

final class TagCollectionResponseTest extends AbstractDTOCollectionResponse
{
    protected function getCollectionClass(): string
    {
        return TagCollectionResponse::class;
    }

    protected function getMemberClass(): string
    {
        return TagResponse::class;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getCollectionData(): array
    {
        $tagResponse = new TagResponse(
            ldId: '/api/v1/stores/123/tags/123',
            ldType: LdType::Tag,
            uuid: Uuid::fromString('bfd2639c-7793-426a-a413-ea262e582208'),
            createdAt: new DateTimeImmutable('2021-01-01 00:00:00'),
            updatedAt: new DateTimeImmutable('2021-01-01 00:00:00'),
            name: 'Tag',
            store: '/api/v1/stores/123',
        );

        return [
            'ldContext' => '/api/v1/contexts/Tag',
            'ldId' => '/api/v1/stores/123/tags',
            'ldType' => LdType::Collection,
            'ldMembers' => [$tagResponse],
            'ldTotalItems' => 1,
            'ldView' => new PartialCollectionView(
                ldId: '/api/v1/stores/123/tags?page=1',
                ldFirst: '/api/v1/stores/123/tags?page=1',
                ldLast: '/api/v1/stores/123/tags?page=1',
                ldNext: null,
                ldPrevious: null
            ),
        ];
    }
}
