<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Response\Variant;

use Apiera\Sdk\Attribute\JsonLdResponseField;
use Apiera\Sdk\Attribute\ResponseField;
use Apiera\Sdk\DTO\Response\AbstractResponse;
use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Enum\VariantStatus;
use Apiera\Sdk\Transformer\DateTimeTransformer;
use Apiera\Sdk\Transformer\LdTypeTransformer;
use Apiera\Sdk\Transformer\UuidTransformer;
use Apiera\Sdk\Transformer\VariantStatusTransformer;
use DateTimeInterface;
use Symfony\Component\Uid\Uuid;

/**
 * @author Marie Rinden <marie@shoppingnorge.no>
 * @since 1.0.0
 */
final readonly class VariantResponse extends AbstractResponse
{
    /**
     * @param string[] $attributeTerms
     * @param string[] $images
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
        #[ResponseField('status', VariantStatusTransformer::class)]
        private VariantStatus $status,
        #[ResponseField('store')]
        private string $store,
        #[ResponseField('product')]
        private string $product,
        #[ResponseField('sku')]
        private string $sku,
        #[ResponseField('price', null)]
        private ?string $price = null,
        #[ResponseField('salePrice', null)]
        private ?string $salePrice = null,
        #[ResponseField('description', null)]
        private ?string $description = null,
        #[ResponseField('weight', null)]
        private ?string $weight = null,
        #[ResponseField('length', null)]
        private ?string $length = null,
        #[ResponseField('width', null)]
        private ?string $width = null,
        #[ResponseField('height', null)]
        private ?string $height = null,
        #[ResponseField('attributeTerms')]
        private array $attributeTerms = [],
        #[ResponseField('images')]
        private array $images = [],
    ) {
        parent::__construct(
            $this->ldId,
            $this->ldType,
            $this->uuid,
            $this->createdAt,
            $this->updatedAt
        );
    }

    public function getId(): string
    {
        return $this->ldId;
    }

    public function getType(): LdType
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

    public function getStatus(): VariantStatus
    {
        return $this->status;
    }

    public function getStore(): string
    {
        return $this->store;
    }

    public function getProduct(): string
    {
        return $this->product;
    }

    public function getSku(): string
    {
        return $this->sku;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function getSalePrice(): ?string
    {
        return $this->salePrice;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getWeight(): ?string
    {
        return $this->weight;
    }

    public function getLength(): ?string
    {
        return $this->length;
    }

    public function getWidth(): ?string
    {
        return $this->width;
    }

    public function getHeight(): ?string
    {
        return $this->height;
    }

    /**
     * @return string[]
     */
    public function getAttributeTerms(): array
    {
        return $this->attributeTerms;
    }

    /**
     * @return string[]
     */
    public function getImages(): array
    {
        return $this->images;
    }
}
