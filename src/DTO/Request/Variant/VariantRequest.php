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
     * @param VariantStatus|null $status The variant status
     * @param string|null $store The variant store
     * @param string|null $product The variant product
     * @param string|null $sku The variant sku
     * @param string|null $price The variant price
     * @param string|null $salePrice The variant salePrice
     * @param string|null $description The variant description
     * @param string|null $weight The variant weight
     * @param string|null $length The variant length
     * @param string|null $width The variant width
     * @param string|null $height The variant height
     * @param string [] $attributeTerms
     * @param string [] $images
     * @param string|null $iri The variant iri
     */
    public function __construct(
        #[RequestField('status', VariantStatusTransformer::class)]
        private ?VariantStatus $status = null,
        #[RequestField('store')]
        private ?string $store = null,
        #[RequestField('product')]
        private ?string $product = null,
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
        #[SkipRequest]
        private ?string $iri = null,
    ) {
    }

    public function getStatus(): ?VariantStatus
    {
        return $this->status;
    }

    public function getStore(): ?string
    {
        return $this->store;
    }

    public function getProduct(): ?string
    {
        return $this->product;
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
            'store' => $this->store,
            'product' => $this->product,
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
