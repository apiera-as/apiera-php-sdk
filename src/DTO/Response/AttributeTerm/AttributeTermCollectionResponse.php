<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Response\AttributeTerm;

use Apiera\Sdk\DTO\Response\AbstractCollectionResponse;

/**
 * @author Marie Rinden <marie@shoppingnorge.no>
 * @since 1.0.0
 */
final readonly class AttributeTermCollectionResponse extends AbstractCollectionResponse
{
    /**
     * @return array<AttributeTermResponse>
     */
    public function getLdMembers(): array
    {
        /** @var array<AttributeTermResponse> $members */
        $members = parent::getLdMembers();

        return $members;
    }
}
