<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Request\Inventory;

use Apiera\Sdk\Attribute\RequestField;
use Apiera\Sdk\Attribute\SkipRequest;
use Apiera\Sdk\Interface\DTO\RequestInterface;

/**
 * @author Marie Rinden <marie@shoppingnorge.no>
 * @since 1.0.0
 */
final readonly class InventoryRequest implements RequestInterface
{
    public function __construct(
        #[RequestField('quantity')]
        private ?int $quantity = null,
        #[RequestField('sku')]
        private ?string $sku = null,
        #[RequestField('inventoryLocation')]
        private ?string $inventoryLocation = null,
        #[SkipRequest]
        private ?string $iri = null,
    ) {
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function getSku(): ?string
    {
        return $this->sku;
    }

    public function getInventoryLocation(): ?string
    {
        return $this->inventoryLocation;
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
            'quantity' => $this->quantity,
            'sku' => $this->sku,
            'inventoryLocation' => $this->inventoryLocation,
        ];
    }
}
