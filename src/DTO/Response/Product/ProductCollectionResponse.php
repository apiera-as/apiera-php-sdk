<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Response\Product;

use Apiera\Sdk\DTO\Response\AbstractCollectionResponse;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 0.3.0
 */
final readonly class ProductCollectionResponse extends AbstractCollectionResponse
{
    /**
     * @return array<ProductResponse>
     */
    public function getMembers(): array
    {
        /** @var array<ProductResponse> $members */
        $members = parent::getMembers();

        return $members;
    }
}
