<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Request\Attribute;

use Apiera\Sdk\DTO\Request\Attribute\AttributeRequest;
use Tests\Unit\DTO\Request\AbstractDTORequest;

final class AttributeRequestTest extends AbstractDTORequest
{
    protected function getRequestClass(): string
    {
        return AttributeRequest::class;
    }

    protected function getConstructorParams(): array
    {
        return [
            'name' => 'Color',
            'store' => '/api/v1/stores/123',
            'iri' => '/api/v1/stores/123/attributes/321'
        ];
    }
}
