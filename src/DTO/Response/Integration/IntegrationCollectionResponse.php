<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Response\Integration;

use Apiera\Sdk\DTO\Response\AbstractCollectionResponse;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 2.1.0
 */
final readonly class IntegrationCollectionResponse extends AbstractCollectionResponse
{
    /**
     * @return array<IntegrationResponse>
     */
    public function getLdMembers(): array
    {
        /** @var array<IntegrationResponse> $members */
        $members = parent::getLdMembers();

        return $members;
    }
}
