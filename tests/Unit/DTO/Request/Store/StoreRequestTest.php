<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Request\Store;

use Apiera\Sdk\DTO\Request\Store\StoreRequest;
use Tests\Unit\DTO\Request\AbstractDTORequest;

final class StoreRequestTest extends AbstractDTORequest
{
    protected function getRequestClass(): string
    {
        return StoreRequest::class;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getConstructorParams(): array
    {
        return [
            'name' => 'Store',
            'description' => 'Description',
            'organization' => 'string',
            'image' => '/api/v1/files/123',
            'properties' => [
                '/api/v1/stores/123/properties/456',
            ],
            'propertyTerms' => [
                '/api/v1/stores/123/properties/456/terms/789',
            ],
        ];
    }
}
