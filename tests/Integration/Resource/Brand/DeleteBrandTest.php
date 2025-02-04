<?php

declare(strict_types=1);

namespace Integration\Resource\Brand;

use Apiera\Sdk\DTO\Request\Brand\BrandRequest;
use Apiera\Sdk\Enum\LdType;
use Tests\Integration\Resource\AbstractTestDeleteOperation;
use Tests\Integration\Resource\StoreScopedOperationTrait;

final class DeleteBrandTest extends AbstractTestDeleteOperation
{
    use StoreScopedOperationTrait;

    protected function getStoreScopedResourcePath(): string
    {
        return '/brands';
    }

    protected function getResourceType(): string
    {
        return LdType::Brand->value;
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     */
    protected function executeDeleteOperation(): void
    {
        $request = new BrandRequest(
            iri: $this->buildStoreUri('brands', $this->resourceId)
        );

        $this->sdk->brand()->delete($request);
    }
}
