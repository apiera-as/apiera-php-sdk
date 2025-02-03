<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Request\Product;

use Apiera\Sdk\Attribute\RequestField;
use Apiera\Sdk\Attribute\SkipRequest;
use Apiera\Sdk\Enum\ProductStatus;
use Apiera\Sdk\Enum\ProductType;
use Apiera\Sdk\Interface\DTO\RequestInterface;
use Apiera\Sdk\Transformer\ProductStatusTransformer;
use Apiera\Sdk\Transformer\ProductTypeTransformer;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 0.3.0
 */
final readonly class ProductRequest implements RequestInterface
{
    /**
     * Numeric values must be provided as strings to preserve decimal precision when sending to the API.
     * All decimal values should be formatted with exactly 2 decimal places (e.g. "99.99", "0.00").
     *
     * @param string|null $name Product name
     * @param ProductType|null $type Product type (required for create and update operations)
     * @param string|null $price Product price as decimal string (e.g. "99.99")
     * @param string|null $salePrice Product sale price as decimal string (e.g. "79.99")
     * @param string|null $description Full product description
     * @param string|null $shortDescription Brief product description
     * @param string|null $weight Product weight as decimal string (e.g. "1.50")
     * @param string|null $length Product length as decimal string (e.g. "10.00")
     * @param string|null $width Product width as decimal string (e.g. "5.00")
     * @param string|null $height Product height as decimal string (e.g. "2.00")
     * @param ProductStatus|null $status Product status (required for create and update operations)
     * @param string|null $distributor Distributor IRI reference
     * @param string|null $brand Brand IRI reference
     * @param string|null $sku Sku IRI reference (required for create and update operations)
     * @param string|null $image Primary image IRI reference
     * @param string[] $categories Array of category IRI references
     * @param string[] $tags Array of tag IRI references
     * @param string[] $attributes Array of attribute IRI references
     * @param string[] $images Array of additional image IRI references
     * @param string[] $alternateIdentifiers Array of alternate identifier IRI references
     * @param string[] $propertyTerms Array of property term IRI references
     * @param string|null $store Store IRI reference (required for get collection and create operations)
     * @param string|null $iri Product IRI reference (required for get, update and delete operations)
     */
    public function __construct(
        #[RequestField('name')]
        private ?string $name = null,
        #[RequestField('type', ProductTypeTransformer::class)]
        private ?ProductType $type = null,
        #[RequestField('price')]
        private ?string $price = null,
        #[RequestField('salePrice')]
        private ?string $salePrice = null,
        #[RequestField('description')]
        private ?string $description = null,
        #[RequestField('shortDescription')]
        private ?string $shortDescription = null,
        #[RequestField('weight')]
        private ?string $weight = null,
        #[RequestField('length')]
        private ?string $length = null,
        #[RequestField('width')]
        private ?string $width = null,
        #[RequestField('height')]
        private ?string $height = null,
        #[RequestField('status', ProductStatusTransformer::class)]
        private ?ProductStatus $status = null,
        #[RequestField('distributor')]
        private ?string $distributor = null,
        #[RequestField('brand')]
        private ?string $brand = null,
        #[RequestField('sku')]
        private ?string $sku = null,
        #[RequestField('image')]
        private ?string $image = null,
        #[RequestField('categories')]
        private array $categories = [],
        #[RequestField('tags')]
        private array $tags = [],
        #[RequestField('attributes')]
        private array $attributes = [],
        #[RequestField('images')]
        private array $images = [],
        #[RequestField('alternateIdentifiers')]
        private array $alternateIdentifiers = [],
        #[RequestField('propertyTerms')]
        private array $propertyTerms = [],
        #[SkipRequest]
        private ?string $store = null,
        #[SkipRequest]
        private ?string $iri = null
    ) {
    }

    public function getType(): ?ProductType
    {
        return $this->type;
    }

    public function getStatus(): ?ProductStatus
    {
        return $this->status;
    }

    /**
     * Sku IRI reference
     */
    public function getSku(): ?string
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

    /**
     * Store IRI reference
     */
    public function getStore(): ?string
    {
        return $this->store;
    }

    /**
     * Product IRI reference
     */
    public function getIri(): ?string
    {
        return $this->iri;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'type' => $this->type->value ?? null,
            'price' => $this->price,
            'salePrice' => $this->salePrice,
            'description' => $this->description,
            'shortDescription' => $this->shortDescription,
            'weight' => $this->weight,
            'length' => $this->length,
            'width' => $this->width,
            'height' => $this->height,
            'status' => $this->status->value ?? null,
            'distributor' => $this->distributor,
            'brand' => $this->brand,
            'sku' => $this->sku,
            'image' => $this->image,
            'categories' => $this->categories,
            'tags' => $this->tags,
            'attributes' => $this->attributes,
            'images' => $this->images,
            'alternateIdentifiers' => $this->alternateIdentifiers,
            'propertyTerms' => $this->propertyTerms,
        ];
    }
}
