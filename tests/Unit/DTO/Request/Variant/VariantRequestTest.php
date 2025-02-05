<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Request\Variant;

use Apiera\Sdk\DTO\Request\Variant\VariantRequest;
use Apiera\Sdk\Enum\VariantStatus;
use Tests\Unit\DTO\Request\AbstractDTORequest;

final class VariantRequestTest extends AbstractDTORequest
{
    protected function getRequestClass(): string
    {
        return VariantRequest::class;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getConstructorParams(): array
    {
        return [
            'price' => '100.00',
            'salePrice' => '99.00',
            'description' => 'Description',
            'weight' => '100.00',
            'length' => '100.00',
            'width' => '100.00',
            'height' => '100.00',
            'status' => VariantStatus::Active,
            'store' => '/api/v1/stores/123',
            'product' => '/api/v1/stores/123/products/123',
            'sku' => '/api/v1/skus/123',
            'attributeTerms' => [
                '/api/v1/stores/123/attributes/123/terms/456',
            ],
            'images' => [
                '/api/v1/files/123',
            ],
        ];
    }
}
