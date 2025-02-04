<?php

declare(strict_types=1);

namespace Integration\Resource\Property;

use Apiera\Sdk\DTO\Request\Property\PropertyRequest;
use Apiera\Sdk\Enum\LdType;
use Tests\Integration\Resource\AbstractTestDeleteOperation;
use Tests\Integration\Resource\StoreScopedOperationTrait;

final class DeletePropertyTest extends AbstractTestDeleteOperation
{
    use StoreScopedOperationTrait;

    protected function getStoreScopedResourcePath(): string
    {
        return '/properties';
    }

    protected function getResourceType(): string
    {
        return LdType::Property->value;
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     */
    protected function executeDeleteOperation(): void
    {
        $request = new PropertyRequest(
            iri: $this->buildStoreUri('properties', $this->resourceId)
        );

        $this->sdk->property()->delete($request);
    }
}
