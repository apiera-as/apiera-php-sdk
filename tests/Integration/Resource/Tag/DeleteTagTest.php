<?php

declare(strict_types=1);

namespace Tests\Integration\Resource\Tag;

use Apiera\Sdk\DTO\Request\Tag\TagRequest;
use Apiera\Sdk\Enum\LdType;
use Tests\Integration\Resource\AbstractTestDeleteOperation;
use Tests\Integration\Resource\StoreScopedOperationTrait;

final class DeleteTagTest extends AbstractTestDeleteOperation
{
    use StoreScopedOperationTrait;

    protected function getStoreScopedResourcePath(): string
    {
        return '/tags';
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
        $request = new TagRequest(
            iri: $this->buildStoreUri('tags', $this->resourceId)
        );

        $this->sdk->tag()->delete($request);
    }
}
