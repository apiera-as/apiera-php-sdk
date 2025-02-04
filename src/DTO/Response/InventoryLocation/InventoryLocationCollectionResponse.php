<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Response\InventoryLocation;

use Apiera\Sdk\DTO\Response\AbstractCollectionResponse;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 0.3.0
 */
final readonly class InventoryLocationCollectionResponse extends AbstractCollectionResponse
{
    /**
     * @return array<InventoryLocationResponse>
     */
    public function getLdMembers(): array
    {
        /** @var array<InventoryLocationResponse> $members */
        $members = parent::getLdMembers();

        return $members;
    }
}
