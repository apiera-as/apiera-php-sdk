<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Response\Category;

use Apiera\Sdk\DTO\Response\Category\CategoryCollectionResponse;
use Apiera\Sdk\DTO\Response\Category\CategoryResponse;
use Apiera\Sdk\DTO\Response\PartialCollectionView;
use Apiera\Sdk\Enum\LdType;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;
use Tests\Unit\DTO\Response\AbstractDTOCollectionResponse;

final class CategoryCollectionResponseTest extends AbstractDTOCollectionResponse
{
    protected function getCollectionClass(): string
    {
        return CategoryCollectionResponse::class;
    }

    protected function getMemberClass(): string
    {
        return CategoryResponse::class;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getCollectionData(): array
    {
        $categoryResponse = new CategoryResponse(
            ldId: '/api/v1/stores/123/categories/123',
            ldType: LdType::Category,
            uuid: Uuid::fromString('bfd2639c-7793-426a-a413-ea262e582208'),
            createdAt: new DateTimeImmutable('2021-01-01 00:00:00'),
            updatedAt: new DateTimeImmutable('2021-01-01 00:00:00'),
            name: 'Electronics',
            store: '/api/v1/stores/123',
            description: 'Electronics description',
            parent: '/api/v1/stores/123/categories/456',
            image: '/api/v1/files/123',
        );

        return [
            'ldContext' => '/api/v1/contexts/Category',
            'ldId' => '/api/v1/stores/123/categories',
            'ldType' => LdType::Collection,
            'ldMembers' => [$categoryResponse],
            'ldTotalItems' => 1,
            'ldView' => new PartialCollectionView(
                ldId: '/api/v1/stores/123/categories?page=1',
                ldFirst: '/api/v1/stores/123/categories?page=1',
                ldLast: '/api/v1/stores/123/categories?page=1',
                ldNext: null,
                ldPrevious: null
            ),
        ];
    }
}
