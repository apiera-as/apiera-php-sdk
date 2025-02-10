<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Response\Tag;

use Apiera\Sdk\DTO\Response\Tag\TagResponse;
use Apiera\Sdk\Enum\LdType;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;
use Tests\Unit\DTO\Response\AbstractDTOResponse;

final class TagResponseTest extends AbstractDTOResponse
{
    protected function getResponseClass(): string
    {
        return TagResponse::class;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getResponseData(): array
    {
        return [
            'ldId' => '/api/v1/tags/123/tags/123',
            'ldType' => LdType::Tag,
            'uuid' => Uuid::fromString('bfd2639c-7793-426a-a413-ea262e582208'),
            'createdAt' => new DateTimeImmutable('2021-01-01 00:00:00'),
            'updatedAt' => new DateTimeImmutable('2021-01-01 00:00:00'),
            'name' => 'Tag',
            'store' => '/api/v1/stores/123',
            ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getNullableFields(): array
    {
        return [
            'name' => null,
            'store' => null,
        ];
    }

    protected function getExpectedLdType(): LdType
    {
        return LdType::Tag;
    }
}
