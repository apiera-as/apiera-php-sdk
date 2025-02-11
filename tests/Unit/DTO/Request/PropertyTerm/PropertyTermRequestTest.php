<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Request\PropertyTerm;

use Apiera\Sdk\DTO\Request\PropertyTerm\PropertyTermRequest;
use Tests\Unit\DTO\Request\AbstractDTORequest;

final class PropertyTermRequestTest extends AbstractDTORequest
{
    protected function getRequestClass(): string
    {
        return PropertyTermRequest::class;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getConstructorParams(): array
    {
        return [
            'name' => 'PropertyTerm',
            'property' => 'string',
        ];
    }
}
