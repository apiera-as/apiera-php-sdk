<?php

declare(strict_types=1);

namespace Integration\Resource\Distributor;

use Apiera\Sdk\DTO\Request\Distributor\DistributorRequest;
use Apiera\Sdk\Enum\LdType;
use Tests\Integration\Resource\AbstractTestDeleteOperation;
use Tests\Integration\Resource\StoreScopedOperationTrait;

final class DeleteDistributorTest extends AbstractTestDeleteOperation
{
    use StoreScopedOperationTrait;

    protected function getStoreScopedResourcePath(): string
    {
        return '/distributors';
    }

    protected function getResourceType(): string
    {
        return LdType::Distributor->value;
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     */
    protected function executeDeleteOperation(): void
    {
        $request = new DistributorRequest(
            iri: $this->buildStoreUri('distributors', $this->resourceId)
        );

        $this->sdk->distributor()->delete($request);
    }
}
