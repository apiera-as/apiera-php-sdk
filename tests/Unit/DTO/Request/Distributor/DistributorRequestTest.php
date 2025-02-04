<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Request\Distributor;

use Apiera\Sdk\DTO\Request\Distributor\DistributorRequest;
use Tests\Unit\DTO\Request\AbstractDTORequest;

final class DistributorRequestTest extends AbstractDTORequest
{
    protected function getRequestClass(): string
    {
        return DistributorRequest::class;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getConstructorParams(): array
    {
        return [
            'name' => 'Example Distributor',
            'store' => '/api/v1/store/123',
            'iri' => '/api/v1/stores/123/distributors/321',
        ];
    }
}
