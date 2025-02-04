<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Response;

use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Interface\DTO\JsonLDInterface;
use Apiera\Sdk\Interface\DTO\ResponseInterface;
use DateTimeInterface;
use Symfony\Component\Uid\Uuid;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 0.1.0
 */
readonly abstract class AbstractResponse implements JsonLDInterface, ResponseInterface
{
    public function __construct(
        private string $id,
        private LdType $type,
        private Uuid $uuid,
        private DateTimeInterface $createdAt,
        private DateTimeInterface $updatedAt,
    ) {
    }

    public function getLdId(): string
    {
        return $this->id;
    }

    public function getLdType(): LdType
    {
        return $this->type;
    }

    public function getUuid(): Uuid
    {
        return $this->uuid;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updatedAt;
    }
}
