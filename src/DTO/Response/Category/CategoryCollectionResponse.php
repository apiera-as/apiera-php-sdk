<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Response\Category;

use Apiera\Sdk\DTO\Response\AbstractCollectionResponse;

/**
 * @template-extends AbstractCollectionResponse<CategoryResponse>
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @package Apiera\Sdk\DTO\Response\Category
 * @since 0.1.0
 */
final readonly class CategoryCollectionResponse extends AbstractCollectionResponse
{
    /**
     * @return array<CategoryResponse>
     */
    public function getMembers(): array
    {
        /** @var array<CategoryResponse> */
        return parent::getMembers();
    }
}
