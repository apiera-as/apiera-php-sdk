<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Response\Organization;

use Apiera\Sdk\DTO\Response\AbstractCollectionResponse;

/**
 * @author Marie Rinden <marie@shoppingnorge.no>
 * @since 1.0.0
 */
final readonly class OrganizationCollectionResponse extends AbstractCollectionResponse
{
    /**
     * @return array<OrganizationResponse>
     */
    public function getLdMembers(): array
    {
        /** @var array<OrganizationResponse> $members */
        $members = parent::getLdMembers();

        return $members;
    }
}
