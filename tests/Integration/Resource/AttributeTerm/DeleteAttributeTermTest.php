<?php

declare(strict_types=1);

namespace Tests\Integration\Resource\AttributeTerm;

use Apiera\Sdk\DTO\Request\AttributeTerm\AttributeTermRequest;
use Apiera\Sdk\Enum\LdType;
use Tests\Integration\Resource\AbstractTestDeleteOperation;
use Tests\Integration\Resource\StoreScopedOperationTrait;

final class DeleteAttributeTermTest extends AbstractTestDeleteOperation
{
    use StoreScopedOperationTrait;

    protected function getStoreScopedResourcePath(): string
    {
        return sprintf('/attributes/%s/terms', $this->resourceId);
    }

    protected function getResourceType(): string
    {
        return LdType::AttributeTerm->value;
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     */
    protected function executeDeleteOperation(): void
    {
        $request = new AttributeTermRequest(
            iri: $this->buildStoreUri('attributes', $this->resourceId, 'terms', $this->resourceId),
        );

        $this->sdk->attributeTerm()->delete($request);
    }
}
