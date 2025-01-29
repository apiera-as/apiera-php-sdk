<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Response\File;

use Apiera\Sdk\DTO\Response\AbstractResponse;
use Apiera\Sdk\Enum\LdType;
use DateTimeInterface;
use Symfony\Component\Uid\Uuid;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 0.3.0
 */
final readonly class FileResponse extends AbstractResponse
{
    public function __construct(
        string $id,
        LdType $type,
        Uuid $uuid,
        DateTimeInterface $createdAt,
        DateTimeInterface $updatedAt,
        private string $url,
        private ?string $name = null,
    ) {
        parent::__construct(
            $id,
            $type,
            $uuid,
            $createdAt,
            $updatedAt
        );
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getName(): ?string
    {
        return $this->name;
    }
}
