<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Response\Product;

use Apiera\Sdk\Attribute\JsonLdResponseField;
use Apiera\Sdk\Attribute\ResponseField;
use Apiera\Sdk\DTO\Response\AbstractResponse;
use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Enum\ProductStatus;
use Apiera\Sdk\Enum\ProductType;
use Apiera\Sdk\Transformer\DateTimeTransformer;
use Apiera\Sdk\Transformer\LdTypeTransformer;
use Apiera\Sdk\Transformer\ProductStatusTransformer;
use Apiera\Sdk\Transformer\ProductTypeTransformer;
use Apiera\Sdk\Transformer\UuidTransformer;
use DateTimeInterface;
use Symfony\Component\Uid\Uuid;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 0.3.0
 */
final readonly class ProductResponse extends AbstractResponse
{
    /**
     * Numeric values are returned as strings with 2 decimal places precision to preserve exact values.
     * All IRI references are returned as strings pointing to their respective resources.
     *
     * @param string $ldId Product IRI reference
     * @param LdType $ldType Response type (must be Product)
     * @param Uuid $uuid Product UUID
     * @param DateTimeInterface $createdAt Creation timestamp
     * @param DateTimeInterface $updatedAt Last update timestamp
     * @param ProductType $type Product type enumeration
     * @param ProductStatus $status Product status enumeration
     * @param string $store Store IRI reference
     * @param string $sku Sku IRI reference
     * @param string|null $name Product name
     * @param string|null $price Product price as decimal string (e.g. "99.99")
     * @param string|null $salePrice Product sale price as decimal string (e.g. "79.99")
     * @param string|null $description Full product description
     * @param string|null $shortDescription Brief product description
     * @param string|null $weight Product weight as decimal string (e.g. "1.50")
     * @param string|null $length Product length as decimal string (e.g. "10.00")
     * @param string|null $width Product width as decimal string (e.g. "5.00")
     * @param string|null $height Product height as decimal string (e.g. "2.00")
     * @param string|null $distributor Distributor IRI reference
     * @param string|null $brand Brand IRI reference
     * @param string|null $image Primary image IRI reference
     * @param string[] $categories Array of category IRI references
     * @param string[] $tags Array of tag IRI references
     * @param string[] $attributes Array of attribute IRI references
     * @param string[] $images Array of additional image IRI references
     * @param string[] $alternateIdentifiers Array of alternate identifier IRI references
     * @param string[] $propertyTerms Array of property term IRI references
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
        #[ResponseField('type', ProductTypeTransformer::class)]
        private ProductType $type,
        #[ResponseField('status', ProductStatusTransformer::class)]
        private ProductStatus $status,
        #[ResponseField('store')]
        private string $store,
        #[ResponseField('sku')]
        private string $sku,
        #[ResponseField('name')]
        private ?string $name = null,
        #[ResponseField('price')]
        private ?string $price = null,
        #[ResponseField('salePrice')]
        private ?string $salePrice = null,
        #[ResponseField('description')]
        private ?string $description = null,
        #[ResponseField('shortDescription')]
        private ?string $shortDescription = null,
        #[ResponseField('weight')]
        private ?string $weight = null,
        #[ResponseField('length')]
        private ?string $length = null,
        #[ResponseField('width')]
        private ?string $width = null,
        #[ResponseField('height')]
        private ?string $height = null,
        #[ResponseField('distributor')]
        private ?string $distributor = null,
        #[ResponseField('brand')]
        private ?string $brand = null,
        #[ResponseField('image')]
        private ?string $image = null,
        #[ResponseField('categories')]
        private array $categories = [],
        #[ResponseField('tags')]
        private array $tags = [],
        #[ResponseField('attributes')]
        private array $attributes = [],
        #[ResponseField('images')]
        private array $images = [],
        #[ResponseField('alternateIdentifiers')]
        private array $alternateIdentifiers = [],
        #[ResponseField('propertyTerms')]
        private array $propertyTerms = [],
    ) {
        parent::__construct(
            $this->ldId,
            $this->ldType,
            $this->uuid,
            $this->createdAt,
            $this->updatedAt
        );
    }

    public function getType(): ProductType
    {
        return $this->type;
    }

    public function getStatus(): ProductStatus
    {
        return $this->status;
    }

    /**
     * Store IRI reference
     */
    public function getStore(): string
    {
        return $this->store;
    }

    /**
     * Sku IRI reference
     */
    public function getSku(): string
    {
        return $this->sku;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Product price as decimal string (e.g. "99.99")
     */
    public function getPrice(): ?string
    {
        return $this->price;
    }

    /**
     * Product sale price as decimal string (e.g. "79.99")
     */
    public function getSalePrice(): ?string
    {
        return $this->salePrice;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    /**
     * Product weight as decimal string (e.g. "1.50")
     */
    public function getWeight(): ?string
    {
        return $this->weight;
    }

    /**
     * Product length as decimal string (e.g. "10.00")
     */
    public function getLength(): ?string
    {
        return $this->length;
    }

    /**
     * Product width as decimal string (e.g. "5.00")
     */
    public function getWidth(): ?string
    {
        return $this->width;
    }

    /**
     * Product height as decimal string (e.g. "2.00")
     */
    public function getHeight(): ?string
    {
        return $this->height;
    }

    /**
     * Distributor IRI reference
     */
    public function getDistributor(): ?string
    {
        return $this->distributor;
    }

    /**
     * Brand IRI reference
     */
    public function getBrand(): ?string
    {
        return $this->brand;
    }

    /**
     * Primary image IRI reference
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * Array of category IRI references
     *
     * @return string[]
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    /**
     * Array of tag IRI references
     *
     * @return string[]
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * Array of attribute IRI references
     *
     * @return string[]
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * Array of additional image IRI references
     *
     * @return string[]
     */
    public function getImages(): array
    {
        return $this->images;
    }

    /**
     * Array of alternate identifier IRI references
     *
     * @return string[]
     */
    public function getAlternateIdentifiers(): array
    {
        return $this->alternateIdentifiers;
    }

    /**
     * Array of property term IRI references
     *
     * @return string[]
     */
    public function getPropertyTerms(): array
    {
        return $this->propertyTerms;
    }
}
