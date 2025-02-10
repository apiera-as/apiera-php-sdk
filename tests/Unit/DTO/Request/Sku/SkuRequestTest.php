<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Request\Sku;

use Apiera\Sdk\DTO\Request\Sku\SkuRequest;
use Tests\Unit\DTO\Request\AbstractDTORequest;

final class SkuRequestTest extends AbstractDTORequest
{
    protected function getRequestClass(): string
    {
        return SkuRequest::class;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getConstructorParams(): array
    {
        return [
            'code' => 'string',
        ];
    }
}
