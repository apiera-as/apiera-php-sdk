<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Response\Property;

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
final readonly class PropertyResponse extends AbstractResponse
{
    /**
     * @param string $name The property name
     * @param string $store The store iri
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
        #[ResponseField('store')]
        private string $store,
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

    public function getStore(): string
    {
        return $this->store;
    }
}
