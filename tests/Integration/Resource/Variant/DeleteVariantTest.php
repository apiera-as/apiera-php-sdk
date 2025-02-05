<?php

declare(strict_types=1);

namespace Tests\Integration\Resource\Variant;

use Apiera\Sdk\DTO\Request\Variant\VariantRequest;
use Apiera\Sdk\Enum\LdType;
use Tests\Integration\Resource\AbstractTestDeleteOperation;
use Tests\Integration\Resource\StoreScopedOperationTrait;

final class DeleteVariantTest extends AbstractTestDeleteOperation
{
    use StoreScopedOperationTrait;

    protected function getStoreScopedResourcePath(): string
    {
        return sprintf('/products/%s/variants', $this->resourceId);
    }

    protected function getResourceType(): string
    {
        return LdType::Variant->value;
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     */
    protected function executeDeleteOperation(): void
    {
        $request = new VariantRequest(
            iri: $this->buildStoreUri('products', $this->resourceId, 'variants', $this->resourceId),
        );

        $this->sdk->variant()->delete($request);
    }
}
