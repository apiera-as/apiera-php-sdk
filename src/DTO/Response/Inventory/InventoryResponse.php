<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Response\Inventory;

use Apiera\Sdk\DTO\Response\AbstractResponse;
use Apiera\Sdk\Enum\LdType;
use DateTimeInterface;
use Symfony\Component\Uid\Uuid;

/**
 * @author Marie Rinden <marie@shoppingnorge.no>
 * @since 0.3.0
 */
final readonly class InventoryResponse extends AbstractResponse
{
    public function __construct(
        string $id,
        LdType $type,
        Uuid $uuid,
        DateTimeInterface $createdAt,
        DateTimeInterface $updatedAt,
        private ?string $quantity = null,
        private ?string $inventoryLocation = null,
        private ?string $sku = null,
    ) {
        parent::__construct(
            $id,
            $type,
            $uuid,
            $createdAt,
            $updatedAt
        );
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
}
