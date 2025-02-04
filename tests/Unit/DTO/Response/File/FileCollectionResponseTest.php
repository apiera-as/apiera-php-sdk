<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Response\File;

use Apiera\Sdk\DTO\Response\File\FileCollectionResponse;
use Apiera\Sdk\DTO\Response\File\FileResponse;
use Apiera\Sdk\DTO\Response\PartialCollectionView;
use Apiera\Sdk\Enum\LdType;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;
use Tests\Unit\DTO\Response\AbstractDTOCollectionResponse;

final class FileCollectionResponseTest extends AbstractDTOCollectionResponse
{
    protected function getCollectionClass(): string
    {
        return FileCollectionResponse::class;
    }

    protected function getMemberClass(): string
    {
        return FileResponse::class;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getCollectionData(): array
    {
        $fileResponse = new FileResponse(
            ldId: '/api/v1/files/123',
            ldType: LdType::File,
            uuid: Uuid::fromString('bfd2639c-7793-426a-a413-ea262e582208'),
            createdAt: new DateTimeImmutable('2021-01-01 00:00:00'),
            updatedAt: new DateTimeImmutable('2021-01-01 00:00:00'),
            url: 'https://example.com/file.jpg',
            name: 'file.jpg',
        );

        return [
            'ldContext' => '/api/v1/contexts/File',
            'ldId' => '/api/v1/files',
            'ldType' => LdType::Collection,
            'ldMembers' => [$fileResponse],
            'ldTotalItems' => 1,
            'ldView' => new PartialCollectionView(
                ldId: '/api/v1/files?page=1',
                ldFirst: '/api/v1/files?page=1',
                ldLast: '/api/v1/files?page=1',
                ldNext: null,
                ldPrevious: null
            ),
        ];
    }
}
