<?php

declare(strict_types=1);

namespace Tests\Integration\Resource\Product;

use Apiera\Sdk\DTO\Request\Product\ProductRequest;
use Apiera\Sdk\Enum\LdType;
use Tests\Integration\Resource\AbstractTestDeleteOperation;
use Tests\Integration\Resource\StoreScopedOperationTrait;

final class DeleteProductTest extends AbstractTestDeleteOperation
{
    use StoreScopedOperationTrait;

    protected function getStoreScopedResourcePath(): string
    {
        return '/products';
    }

    protected function getResourceType(): string
    {
        return LdType::Product->value;
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     */
    protected function executeDeleteOperation(): void
    {
        $request = new ProductRequest(
            iri: $this->buildStoreUri('products', $this->resourceId)
        );

        $this->sdk->product()->delete($request);
    }
}
