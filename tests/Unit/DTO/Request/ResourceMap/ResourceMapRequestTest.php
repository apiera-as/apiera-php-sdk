<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Request\ResourceMap;

use Apiera\Sdk\DTO\Request\ResourceMap\ResourceMapRequest;
use Tests\Unit\DTO\Request\AbstractDTORequest;

final class ResourceMapRequestTest extends AbstractDTORequest
{
    protected function getRequestClass(): string
    {
        return ResourceMapRequest::class;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getConstructorParams(): array
    {
        return [
            'external' => 'string',
            'resource' => 'string',
            'integration' => 'string',
            'iri' => 'string',
        ];
    }
}
