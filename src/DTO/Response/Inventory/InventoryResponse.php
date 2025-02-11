<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Response\Inventory;

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
 * @since 1.0.0
 */
final readonly class InventoryResponse extends AbstractResponse
{
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
        #[ResponseField('quantity')]
        private int $quantity,
        #[ResponseField('sku')]
        private string $sku,
        #[ResponseField('inventoryLocation')]
        private string $inventoryLocation,
    ) {
        parent::__construct(
            $this->ldId,
            $this->ldType,
            $this->uuid,
            $this->createdAt,
            $this->updatedAt
        );
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getSku(): string
    {
        return $this->sku;
    }

    public function getInventoryLocation(): string
    {
        return $this->inventoryLocation;
    }
}
