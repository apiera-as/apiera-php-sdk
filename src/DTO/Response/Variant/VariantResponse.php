<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Response\Variant;

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
 * @author Marie Rinden <marie@shoppingnorge.no>
 * @since 0.3.0
 */
final readonly class VariantResponse extends AbstractResponse
{
    public function __construct(
        #[JsonLdResponseField('@id')]
        private string $id,
        #[JsonLdResponseField('@type', LdTypeTransformer::class)]
        private LdType $type,
        #[ResponseField('uuid', UuidTransformer::class)]
        private Uuid $uuid,
        #[ResponseField('createdAt', DateTimeTransformer::class)]
        private DateTimeInterface $createdAt,
        #[ResponseField('updatedAt', DateTimeTransformer::class)]
        private DateTimeInterface $updatedAt,
        #[ResponseField('status')]
        private string $status,
        #[ResponseField('store')]
        private string $store,
        #[ResponseField('product')]
        private string $product,
        #[ResponseField('sku')]
        private string $sku,
        #[ResponseField('attributeTerms')]
        private string $attributeTerms,
        #[ResponseField('images')]
        private string $images,
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
        private ?string $height = null
    ) {
        parent::__construct(
            $this->id,
            $this->type,
            $this->uuid,
            $this->createdAt,
            $this->updatedAt
        );
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getType(): LdType
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

    public function getStatus(): string
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

    public function getAttributeTerms(): string
    {
        return $this->attributeTerms;
    }

    public function getImages(): string
    {
        return $this->images;
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
}
