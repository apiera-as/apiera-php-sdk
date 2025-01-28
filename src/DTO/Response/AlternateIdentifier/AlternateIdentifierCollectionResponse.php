<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Response\AlternateIdentifier;

use Apiera\Sdk\DTO\Response\AbstractCollectionResponse;

/**
 * @author Marie Rinden <marie@shoppingnorge.no>
 * @since 0.2.0
 */
final readonly class AlternateIdentifierCollectionResponse extends AbstractCollectionResponse
{
    /**
     * @return array<AlternateIdentifierResponse>
     */
    public function getMembers(): array
    {
        /** @var array<AlternateIdentifierResponse> $members */
        $members = parent::getMembers();

        return $members;
    }
}
