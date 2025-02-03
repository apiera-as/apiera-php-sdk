<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Request\AttributeTerm;

use Apiera\Sdk\DTO\Request\AttributeTerm\AttributeTermRequest;
use Tests\Unit\DTO\Request\AbstractDTORequest;

final class AttributeTermRequestTest extends AbstractDTORequest
{
    protected function getRequestClass(): string
    {
        return AttributeTermRequest::class;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getConstructorParams(): array
    {
        return [
            'name' => 'Green',
            'attribute' => 'ean',
            'iri' => '/api/v1/stores/123/attributes/123/terms/123',
        ];
    }
}
