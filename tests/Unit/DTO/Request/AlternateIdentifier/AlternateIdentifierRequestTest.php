<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Request\AlternateIdentifier;

use Apiera\Sdk\DTO\Request\AlternateIdentifier\AlternateIdentifierRequest;
use Tests\Unit\DTO\Request\AbstractDTORequest;

final class AlternateIdentifierRequestTest extends AbstractDTORequest
{
    protected function getRequestClass(): string
    {
        return AlternateIdentifierRequest::class;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getConstructorParams(): array
    {
        return [
            'code' => '123',
            'type' => 'ean',
            'iri' => '/api/v1/alternate_identifiers/123',
        ];
    }
}
