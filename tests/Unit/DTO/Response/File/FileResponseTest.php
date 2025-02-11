<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Response\File;

use Apiera\Sdk\DTO\Response\File\FileResponse;
use Apiera\Sdk\Enum\LdType;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;
use Tests\Unit\DTO\Response\AbstractDTOResponse;

final class FileResponseTest extends AbstractDTOResponse
{
    protected function getResponseClass(): string
    {
        return FileResponse::class;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getResponseData(): array
    {
        return [
            'ldId' => '/api/v1/files/123',
            'ldType' => LdType::File,
            'uuid' => Uuid::fromString('bfd2639c-7793-426a-a413-ea262e582208'),
            'createdAt' => new DateTimeImmutable('2021-01-01 00:00:00'),
            'updatedAt' => new DateTimeImmutable('2021-01-01 00:00:00'),
            'url' => 'https://example.com/file.jpg',
            'name' => 'file.jpg',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getNullableFields(): array
    {
        return [
            'name' => null,
        ];
    }

    protected function getExpectedLdType(): LdType
    {
        return LdType::File;
    }
}
