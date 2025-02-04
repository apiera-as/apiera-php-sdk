<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Request\Inventory;

use Apiera\Sdk\Interface\DTO\RequestInterface;

/**
 * @author Marie Rinden <marie@shoppingnorge.no>
 * @since 0.3.0
 */
final readonly class InventoryRequest implements RequestInterface
{
    public function __construct(
        private int $quantity,
        private string $sku,
        private ?string $inventoryLocation = null,
        private ?string $iri = null,
    ) {
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getSku(): string
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
        ];
    }
}
