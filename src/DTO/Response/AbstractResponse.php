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
 * @package Apiera\Sdk\DTO\Response
 * @since 0.1.0
 */
readonly abstract class AbstractResponse implements JsonLDInterface, ResponseInterface
{
    /**
     * @param string $id
     * @param LdType $type
     * @param Uuid $uuid
     * @param DateTimeInterface $createdAt
     * @param DateTimeInterface $updatedAt
     */
    public function __construct(
        private string $id,
        private LdType $type,
        private Uuid $uuid,
        private DateTimeInterface $createdAt,
        private DateTimeInterface $updatedAt,
    ) {
    }

    /**
     * @return string
     */
    public function getLdId(): string
    {
        return $this->id;
    }

    /**
     * @return LdType
     */
    public function getLdType(): LdType
    {
        return $this->type;
    }

    /**
     * @return Uuid
     */
    public function getUuid(): Uuid
    {
        return $this->uuid;
    }

    /**
     * @return DateTimeInterface
     */
    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @return DateTimeInterface
     */
    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updatedAt;
    }
}
