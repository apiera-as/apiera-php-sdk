<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Request\Organization;

use Apiera\Sdk\DTO\Request\Organization\OrganizationRequest;
use Tests\Unit\DTO\Request\AbstractDTORequest;

final class OrganizationRequestTest extends AbstractDTORequest
{
    protected function getRequestClass(): string
    {
        return OrganizationRequest::class;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getConstructorParams(): array
    {
        return [
            'name' => 'Organization',
            'extId' => 'string',
            'alternateIdentifiers' => [
                '/api/v1/alternate_identifiers/345',
            ],
        ];
    }
}
