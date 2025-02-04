<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Request\Brand;

use Apiera\Sdk\DTO\Request\Brand\BrandRequest;
use Tests\Unit\DTO\Request\AbstractDTORequest;

final class BrandRequestTest extends AbstractDTORequest
{
    protected function getRequestClass(): string
    {
        return BrandRequest::class;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getConstructorParams(): array
    {
        return [
            'name' => 'Apiera',
            'description' => 'SaaS company',
            'image' => '/api/v1/files/789',
            'store' => '/api/v1/stores/123',
            'iri' => '/api/v1/stores/123/brands/321',
        ];
    }
}
