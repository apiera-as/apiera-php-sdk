<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Response\Attribute;

use Apiera\Sdk\DTO\Response\AbstractCollectionResponse;

/**
 * @template-extends AbstractCollectionResponse<AttributeResponse>
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @package Apiera\Sdk\DTO\Response\Attribute
 * @since 0.2.0
 */
final readonly class AttributeCollectionResponse extends AbstractCollectionResponse
{
    /**
     * @return array<AttributeResponse>
     */
    public function getMembers(): array
    {
        /** @var array<AttributeResponse> */
        return parent::getMembers();
    }
}
