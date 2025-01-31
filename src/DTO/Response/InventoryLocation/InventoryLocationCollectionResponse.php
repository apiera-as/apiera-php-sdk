<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Response\InventoryLocation;

use Apiera\Sdk\DTO\Response\AbstractCollectionResponse;

/**
 * @author Marie Rinden <marie@shoppingnorge.no>
 * @since 0.3.0
 */
final readonly class InventoryLocationCollectionResponse extends AbstractCollectionResponse
{
    /**
     * @return array<InventoryLocationResponse>
     */
    public function getMembers(): array
    {
        /** @var array<InventoryLocationResponse> $members */
        $members = parent::getMembers();

        return $members;
    }
}
