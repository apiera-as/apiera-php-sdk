<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Response\Category;

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
 * @since 0.1.0
 */
final readonly class CategoryResponse extends AbstractResponse
{
    /**
     * @param string $name The category name
     * @param string $store The category store iri
     * @param string|null $description The category description
     * @param string|null $parent The category parent iri
     * @param string|null $image The category image iri
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
        #[ResponseField('description', null)]
        private ?string $description = null,
        #[ResponseField('parent', null)]
        private ?string $parent = null,
        #[ResponseField('image', null)]
        private ?string $image = null
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

    public function getParent(): ?string
    {
        return $this->parent;
    }

    public function getStore(): string
    {
        return $this->store;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }
}
