<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Request\Property;

use Apiera\Sdk\DTO\Request\Property\PropertyRequest;
use Tests\Unit\DTO\Request\AbstractDTORequest;

final class PropertyRequestTest extends AbstractDTORequest
{
    protected function getRequestClass(): string
    {
        return PropertyRequest::class;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getConstructorParams(): array
    {
        return [
            'name' => 'Material',
            'store' => '/api/v1/store/123',
            'iri' => '/api/v1/stores/123/properties/321',
        ];
    }
}
