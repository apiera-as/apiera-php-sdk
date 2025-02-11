<?php

declare(strict_types=1);

namespace Tests\Integration\Resource\ResourceMap;

use Apiera\Sdk\DTO\Request\ResourceMap\ResourceMapRequest;
use Apiera\Sdk\Enum\LdType;
use Tests\Integration\Resource\AbstractTestDeleteOperation;
use Tests\Integration\Resource\IntegrationScopedOperationTrait;

final class DeleteResourceMapTest extends AbstractTestDeleteOperation
{
    use IntegrationScopedOperationTrait;

    protected function getIntegrationScopedResourcePath(): string
    {
        return '/mappings';
    }

    protected function getResourceType(): string
    {
        return LdType::IntegrationResourceMap->value;
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     */
    protected function executeDeleteOperation(): void
    {
        $request = new ResourceMapRequest(
            iri: $this->buildIntegrationUri('mappings', $this->resourceId)
        );

        $this->sdk->resourceMap()->delete($request);
    }
}
