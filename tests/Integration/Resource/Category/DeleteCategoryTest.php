<?php

declare(strict_types=1);

namespace Tests\Integration\Resource\Category;

use Apiera\Sdk\DTO\Request\Category\CategoryRequest;
use Apiera\Sdk\Enum\LdType;
use Tests\Integration\Resource\AbstractTestDeleteOperation;
use Tests\Integration\Resource\StoreScopedOperationTrait;

final class DeleteCategoryTest extends AbstractTestDeleteOperation
{
    use StoreScopedOperationTrait;

    protected function getStoreScopedResourcePath(): string
    {
        return '/categories';
    }

    protected function getResourceType(): string
    {
        return LdType::Category->value;
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     */
    protected function executeDeleteOperation(): void
    {
        $request = new CategoryRequest(
            iri: $this->buildStoreUri('categories', $this->resourceId)
        );

        $this->sdk->category()->delete($request);
    }
}
