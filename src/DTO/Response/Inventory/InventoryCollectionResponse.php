<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Response\Inventory;

use Apiera\Sdk\DTO\Response\AbstractCollectionResponse;

/**
 * @author Marie Rinden <marie@shoppingnorge.no>
 * @since 1.0.0
 */
final readonly class InventoryCollectionResponse extends AbstractCollectionResponse
{
    /**
     * @return array<InventoryResponse>
     */
    public function getLdMembers(): array
    {
        /** @var array<InventoryResponse> $members */
        $members = parent::getLdMembers();

        return $members;
    }
}
