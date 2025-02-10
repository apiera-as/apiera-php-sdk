<?php

declare(strict_types=1);

namespace Tests\Integration\Resource\PropertyTerm;

use Apiera\Sdk\DTO\Request\PropertyTerm\PropertyTermRequest;
use Apiera\Sdk\Enum\LdType;
use Tests\Integration\Resource\AbstractTestDeleteOperation;
use Tests\Integration\Resource\StoreScopedOperationTrait;

final class DeletePropertyTermTest extends AbstractTestDeleteOperation
{
    use StoreScopedOperationTrait;

    protected function getStoreScopedResourcePath(): string
    {
        return sprintf('/properties/%s/terms', $this->resourceId);
    }

    protected function getResourceType(): string
    {
        return LdType::PropertyTerm->value;
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     */
    protected function executeDeleteOperation(): void
    {
        $request = new PropertyTermRequest(
            iri: $this->buildStoreUri('properties', $this->resourceId, 'terms', $this->resourceId),
        );

        $this->sdk->propertyTerm()->delete($request);
    }
}
