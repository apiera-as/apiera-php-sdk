<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Response\Attribute;

use Apiera\Sdk\DTO\Response\AbstractCollectionResponse;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 0.2.0
 */
final readonly class AttributeCollectionResponse extends AbstractCollectionResponse
{
    /**
     * @return array<AttributeResponse>
     */
    public function getLdMembers(): array
    {
        /** @var array<AttributeResponse> $members */
        $members = parent::getLdMembers();

        return $members;
    }
}
