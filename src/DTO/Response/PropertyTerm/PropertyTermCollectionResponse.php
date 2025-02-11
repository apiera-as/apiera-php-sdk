<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Response\PropertyTerm;

use Apiera\Sdk\DTO\Response\AbstractCollectionResponse;

/**
 * @author Marie Rinden <marie@shoppingnorge.no>
 * @since 1.0.0
 */
final readonly class PropertyTermCollectionResponse extends AbstractCollectionResponse
{
    /**
     * @return array<PropertyTermResponse>
     */
    public function getLdMembers(): array
    {
        /** @var array<PropertyTermResponse> $members */
        $members = parent::getLdMembers();

        return $members;
    }
}
