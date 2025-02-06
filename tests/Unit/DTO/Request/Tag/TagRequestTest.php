<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Request\Tag;

use Apiera\Sdk\DTO\Request\Tag\TagRequest;
use Tests\Unit\DTO\Request\AbstractDTORequest;

final class TagRequestTest extends AbstractDTORequest
{
    protected function getRequestClass(): string
    {
        return TagRequest::class;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getConstructorParams(): array
    {
        return [
            'name' => 'Tag',
            'store' => '/api/v1/stores/123',
        ];
    }
}
