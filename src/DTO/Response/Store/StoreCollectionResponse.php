<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Response\Store;

use Apiera\Sdk\DTO\Response\AbstractCollectionResponse;

/**
 * @author Marie Rinden <marie@shoppingnorge.no>
 * @since 0.3.0
 */
final readonly class StoreCollectionResponse extends AbstractCollectionResponse
{
    /**
     * @return array<StoreResponse>
     */
    public function getLdMembers(): array
    {
        /** @var array<StoreResponse> $members */
        $members = parent::getLdMembers();

        return $members;
    }
}
