<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Request\Category;

use Apiera\Sdk\DTO\Request\Category\CategoryRequest;
use Tests\Unit\DTO\Request\AbstractDTORequest;

final class CategoryRequestTest extends AbstractDTORequest
{
    protected function getRequestClass(): string
    {
        return CategoryRequest::class;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getConstructorParams(): array
    {
        return [
            'name' => 'Electronics',
            'description' => 'Electronic products',
            'parent' => '/api/v1/stores/123/categories/456',
            'image' => '/api/v1/files/789',
            'store' => '/api/v1/stores/123',
            'iri' => '/api/v1/stores/123/categories/321',
        ];
    }
}
