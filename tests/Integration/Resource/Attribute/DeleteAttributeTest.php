<?php

declare(strict_types=1);

namespace Tests\Integration\Resource\Attribute;

use Apiera\Sdk\DTO\Request\Attribute\AttributeRequest;
use Apiera\Sdk\Enum\LdType;
use Tests\Integration\Resource\AbstractTestDeleteOperation;
use Tests\Integration\Resource\StoreScopedOperationTrait;

final class DeleteAttributeTest extends AbstractTestDeleteOperation
{
    use StoreScopedOperationTrait;

    protected function getStoreScopedResourcePath(): string
    {
        return '/attributes';
    }

    protected function getResourceType(): string
    {
        return LdType::Attribute->value;
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     */
    protected function executeDeleteOperation(): void
    {
        $request = new AttributeRequest(
            iri: $this->buildStoreUri('attributes', $this->resourceId)
        );

        $this->sdk->attribute()->delete($request);
    }
}
