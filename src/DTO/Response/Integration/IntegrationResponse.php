<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Response\Integration;

use Apiera\Sdk\Attribute\JsonLdResponseField;
use Apiera\Sdk\Attribute\ResponseField;
use Apiera\Sdk\DTO\Response\AbstractResponse;
use Apiera\Sdk\Enum\IntegrationProtocol;
use Apiera\Sdk\Enum\IntegrationStatus;
use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Transformer\DateTimeTransformer;
use Apiera\Sdk\Transformer\IntegrationProtocolTransformer;
use Apiera\Sdk\Transformer\IntegrationStatusTransformer;
use Apiera\Sdk\Transformer\LdTypeTransformer;
use Apiera\Sdk\Transformer\UuidTransformer;
use DateTimeInterface;
use Symfony\Component\Uid\Uuid;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 2.1.0
 */
final readonly class IntegrationResponse extends AbstractResponse
{
    /**
     * @param string $ldId Integration IRI reference
     * @param LdType $ldType Response type (must be Integration)
     * @param Uuid $uuid Integration UUID
     * @param DateTimeInterface $createdAt Creation timestamp
     * @param DateTimeInterface $updatedAt Last update timestamp
     * @param string $name Integration name
     * @param IntegrationProtocol $protocol Integration protocol enumeration
     * @param IntegrationStatus $status Integration status enumeration
     */
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
        #[ResponseField('name')]
        private string $name,
        #[ResponseField('protocol', IntegrationProtocolTransformer::class)]
        private IntegrationProtocol $protocol,
        #[ResponseField('status', IntegrationStatusTransformer::class)]
        private IntegrationStatus $status,
    ) {
        parent::__construct(
            $this->ldId,
            $this->ldType,
            $this->uuid,
            $this->createdAt,
            $this->updatedAt
        );
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getProtocol(): IntegrationProtocol
    {
        return $this->protocol;
    }

    public function getStatus(): IntegrationStatus
    {
        return $this->status;
    }
}
