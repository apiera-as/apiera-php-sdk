<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Response\Product;

use Apiera\Sdk\DTO\Response\AbstractCollectionResponse;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 1.0.0
 */
final readonly class ProductCollectionResponse extends AbstractCollectionResponse
{
    /**
     * @return array<ProductResponse>
     */
    public function getLdMembers(): array
    {
        /** @var array<ProductResponse> $members */
        $members = parent::getLdMembers();

        return $members;
    }
}
