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
        private string $id,
        private string $type,
        private ?string $quantity = null,
        private ?string $inventoryLocation = null,
        private ?string $sku = null,
        private ?string $iri = null,
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getQuantity(): ?string
    {
        return $this->quantity;
    }

    public function getInventoryLocation(): ?string
    {
        return $this->inventoryLocation;
    }

    public function getSku(): ?string
    {
        return $this->sku;
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
            'id' => $this->id,
            'type' => $this->type,
            'quantity' => $this->quantity,
            'inventoryLocation' => $this->inventoryLocation,
            'sku' => $this->sku,
        ];
    }
}
