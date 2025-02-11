<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Response\ResourceMap;

use Apiera\Sdk\Attribute\JsonLdResponseField;
use Apiera\Sdk\Attribute\ResponseField;
use Apiera\Sdk\DTO\Response\AbstractResponse;
use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Transformer\DateTimeTransformer;
use Apiera\Sdk\Transformer\LdTypeTransformer;
use Apiera\Sdk\Transformer\UuidTransformer;
use DateTimeInterface;
use Symfony\Component\Uid\Uuid;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 1.0.0
 */
final readonly class ResourceMapResponse extends AbstractResponse
{
    public function __construct(
        #[JsonLdResponseField('@id')]
        private string $ldId,
        #[JsonLdResponseField('@type', LdTypeTransformer::class)]
        private LdType $ldType,
        #[ResponseField('uuid', UuidTransformer::class)]
        private Uuid $uuid,
        #[ResponseField('createdAt', DateTimeTransformer::class)]
        private DateTimeInterface $createdAt,
        #[ResponseField('updatedAt', DateTimeTransformer::class)]
        private DateTimeInterface $updatedAt,
        #[ResponseField('external')]
        private string $external,
        #[ResponseField('internal')]
        private string $internal,
        #[ResponseField('resource')]
        private string $resource,
        #[ResponseField('resourceType')]
        private string $resourceType,
        #[ResponseField('integration')]
        private string $integration,
    ) {
        parent::__construct(
            $this->ldId,
            $this->ldType,
            $this->uuid,
            $this->createdAt,
            $this->updatedAt
        );
    }

    public function getLdId(): string
    {
        return $this->ldId;
    }

    public function getLdType(): LdType
    {
        return $this->ldType;
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

    public function getExternal(): string
    {
        return $this->external;
    }

    public function getInternal(): string
    {
        return $this->internal;
    }

    public function getResource(): string
    {
        return $this->resource;
    }

    public function getResourceType(): string
    {
        return $this->resourceType;
    }

    public function getIntegration(): string
    {
        return $this->integration;
    }
}
