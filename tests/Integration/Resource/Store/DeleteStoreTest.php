<?php

declare(strict_types=1);

namespace Tests\Integration\Resource\Store;

use Apiera\Sdk\DTO\Request\Store\StoreRequest;
use Apiera\Sdk\Enum\LdType;
use Tests\Integration\Resource\AbstractTestDeleteOperation;
use Tests\Integration\Resource\ResourceOperationTrait;

final class DeleteStoreTest extends AbstractTestDeleteOperation
{
    use ResourceOperationTrait;

    protected function getResourcePath(): string
    {
        return '/stores';
    }

    protected function getResourceType(): string
    {
        return LdType::AlternateIdentifier->value;
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     */
    protected function executeDeleteOperation(): void
    {
        $request = new StoreRequest(
            iri: $this->buildUri('stores', $this->resourceId)
        );

        $this->sdk->store()->delete($request);
    }
}
