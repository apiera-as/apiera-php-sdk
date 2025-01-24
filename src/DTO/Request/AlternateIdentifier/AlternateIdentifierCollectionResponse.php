<?php

namespace Apiera\Sdk\DTO\Request\AlternateIdentifier;

use Apiera\Sdk\DTO\Response\AbstractCollectionResponse;
use Apiera\Sdk\DTO\Response\AlternateIdentifier\AlternateIdentifierResponse;

/**
 * @template-extends AbstractCollectionResponse<AlternateIdentifierResponse>
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
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
