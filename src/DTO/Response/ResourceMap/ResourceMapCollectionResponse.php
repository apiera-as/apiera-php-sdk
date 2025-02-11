<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Response\ResourceMap;

use Apiera\Sdk\DTO\Response\AbstractCollectionResponse;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 0.3.0
 */
final readonly class ResourceMapCollectionResponse extends AbstractCollectionResponse
{
    /**
     * @return array<ResourceMapResponse>
     */
    public function getLdMembers(): array
    {
        /** @var array<ResourceMapResponse> $members */
        $members = parent::getLdMembers();

        return $members;
    }
}
