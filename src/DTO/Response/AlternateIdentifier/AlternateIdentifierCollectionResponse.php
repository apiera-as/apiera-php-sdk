<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Response\AlternateIdentifier;

use Apiera\Sdk\DTO\Response\AbstractCollectionResponse;

/**
 * @template-extends AbstractCollectionResponse<AlternateIdentifierResponse>
 * @author Marie Rinden <marie@shoppingnorge.no>
 * @package Apiera\Sdk\DTO\Response\AlternateIdentifier
 * @since 0.2.0
 */
final readonly class AlternateIdentifierCollectionResponse extends AbstractCollectionResponse
{
    /**
     * @return array<AlternateIdentifierResponse>
     */
    public function getMembers(): array
    {
        /** @var array<AlternateIdentifierResponse> */
        return parent::getMembers();
    }
}
