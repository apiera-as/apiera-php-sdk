<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Response\Sku;

use Apiera\Sdk\DTO\Response\AbstractCollectionResponse;

/**
 * @author Marie Rinden <marie@shoppingnorge.no>
 * @since 0.3.0
 */
final readonly class SkuCollectionResponse extends AbstractCollectionResponse
{
    /**
     * @return array<SkuResponse>
     */
    public function getLdMembers(): array
    {
        /** @var array<SkuResponse> $members */
        $members = parent::getLdMembers();

        return $members;
    }
}
