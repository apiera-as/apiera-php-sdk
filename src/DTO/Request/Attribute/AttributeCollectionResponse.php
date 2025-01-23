<?php

namespace Apiera\Sdk\DTO\Request\Attribute;

use Apiera\Sdk\DTO\Response\AbstractCollectionResponse;
use Apiera\Sdk\DTO\Response\Attribute\AttributeResponse;

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
