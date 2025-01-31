<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Response\AttributeTerm;

use Apiera\Sdk\DTO\Response\AbstractCollectionResponse;

/**
 * @author Marie Rinden <marie@shoppingnorge.no>
 * @since 0.3.0
 */
final readonly class AttributeTermCollectionResponse extends AbstractCollectionResponse
{
    /**
     * @return array<AttributeTermResponse>
     */
    public function getMembers(): array
    {
        /** @var array<AttributeTermResponse> $members */
        $members = parent::getMembers();

        return $members;
    }
}
