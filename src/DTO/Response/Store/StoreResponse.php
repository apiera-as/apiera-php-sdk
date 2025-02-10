<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Response\Store;

use Apiera\Sdk\Attribute\JsonLdResponseField;
use Apiera\Sdk\Attribute\RequestField;
use Apiera\Sdk\Attribute\ResponseField;
use Apiera\Sdk\DTO\Response\AbstractResponse;
use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Transformer\DateTimeTransformer;
use Apiera\Sdk\Transformer\LdTypeTransformer;
use Apiera\Sdk\Transformer\UuidTransformer;
use DateTimeInterface;
use Symfony\Component\Uid\Uuid;

/**
 * @author Marie Rinden <marie@shoppingnorge.no>
 * @since 0.3.0
 */
final readonly class StoreResponse extends AbstractResponse
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
        #[RequestField('name')]
        private ?string $name,
        #[RequestField('description')]
        private ?string $description = null,
        #[RequestField('image')]
        private ?string $image = null,
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }
}
