<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Response\Brand;

use Apiera\Sdk\DTO\Response\AbstractCollectionResponse;

/**
 * @author Marie Rinden <marie@shoppingnorge.no>
 * @since 1.0.0
*/
final readonly class BrandCollectionResponse extends AbstractCollectionResponse
{
    /**
     * @return array<BrandResponse>
     */
    public function getLdMembers(): array
    {
        /** @var array<BrandResponse> $members */
        $members = parent::getLdMembers();

        return $members;
    }
}
