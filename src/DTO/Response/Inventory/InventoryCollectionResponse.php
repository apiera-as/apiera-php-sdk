<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Response\Inventory;

use Apiera\Sdk\DTO\Response\AbstractCollectionResponse;

/**
 * @author Marie Rinden <marie@shoppingnorge.no>
 * @since 0.3.0
 */
final readonly class InventoryCollectionResponse extends AbstractCollectionResponse
{
    /**
     * @return InventoryResponse[]
     */
    public function getMembers(): array
    {
        /** @var array<InventoryResponse> $members */
        $members = parent::getMembers();

        return $members;
    }
}
