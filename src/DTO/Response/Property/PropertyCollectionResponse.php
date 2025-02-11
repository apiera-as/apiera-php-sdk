<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Response\Property;

use Apiera\Sdk\DTO\Response\AbstractCollectionResponse;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 1.0.0
 */
final readonly class PropertyCollectionResponse extends AbstractCollectionResponse
{
    /**
     * @return array<PropertyResponse>
     */
    public function getLdMembers(): array
    {
        /** @var array<PropertyResponse> $members */
        $members = parent::getLdMembers();

        return $members;
    }
}
