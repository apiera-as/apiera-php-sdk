<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Response\Variant;

use Apiera\Sdk\DTO\Response\AbstractCollectionResponse;

/**
 * @author Marie Rinden <marie@shoppingnorge.no>
 * @since 1.0.0
 */
final readonly class VariantCollectionResponse extends AbstractCollectionResponse
{
    /**
     * @return array<VariantResponse>
     */
    public function getLdMembers(): array
    {
        /** @var array<VariantResponse> $members */
        $members = parent::getLdMembers();

        return $members;
    }
}
