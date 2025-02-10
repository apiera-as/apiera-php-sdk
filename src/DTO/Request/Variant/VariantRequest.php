<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Request\Variant;

use Apiera\Sdk\Attribute\RequestField;
use Apiera\Sdk\Attribute\SkipRequest;
use Apiera\Sdk\Enum\VariantStatus;
use Apiera\Sdk\Interface\DTO\RequestInterface;
use Apiera\Sdk\Transformer\VariantStatusTransformer;

/**
 * @author Marie Rinden <marie@shoppingnorge.no>
 * @since 0.3.0
 */
final readonly class VariantRequest implements RequestInterface
{
    /**
     * @param string|null $sku Sku IRI reference
     * @param string|null $price Product price as decimal string (e.g. "99.99")
     * @param string|null $salePrice Product sale price as decimal string (e.g. "79.99")
     * @param string|null $weight Product weight as decimal string (e.g. "1.50")
     * @param string|null $length Product length as decimal string (e.g. "10.00")
     * @param string|null $width Product width as decimal string (e.g. "5.00")
     * @param string|null $height Product height as decimal string (e.g. "2.00")
     * @param string[] $attributeTerms Array of attribute term IRI references
     * @param string[] $images Array of image IRI references
     * @param string|null $product Product IRI reference (required for get collection and create operations)
     * @param string|null $iri Resource IRI reference (required for get, update and delete operations)
     */
    public function __construct(
        #[RequestField('status', VariantStatusTransformer::class)]
        private ?VariantStatus $status = null,
        #[RequestField('sku')]
        private ?string $sku = null,
        #[RequestField('price')]
        private ?string $price = null,
        #[RequestField('salePrice')]
        private ?string $salePrice = null,
        #[RequestField('description')]
        private ?string $description = null,
        #[RequestField('weight')]
        private ?string $weight = null,
        #[RequestField('length')]
        private ?string $length = null,
        #[RequestField('width')]
        private ?string $width = null,
        #[RequestField('height')]
        private ?string $height = null,
        #[RequestField('attributeTerms')]
        private array $attributeTerms = [],
        #[RequestField('images')]
        private array $images = [],
        #[RequestField('product')]
        private ?string $product = null,
        #[SkipRequest]
        private ?string $iri = null,
    ) {
    }

    public function getStatus(): ?VariantStatus
    {
        return $this->status;
    }

    public function getSku(): ?string
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

    public function getProduct(): ?string
    {
        return $this->product;
    }

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
            'status' => $this->status,
            'sku' => $this->sku,
            'attributeTerms' => $this->attributeTerms,
            'images' => $this->images,
            'price' => $this->price,
            'salePrice' => $this->salePrice,
            'description' => $this->description,
            'weight' => $this->weight,
            'length' => $this->length,
            'width' => $this->width,
            'height' => $this->height,
        ];
    }
}
