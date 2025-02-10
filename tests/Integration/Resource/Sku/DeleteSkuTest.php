<?php

declare(strict_types=1);

namespace Tests\Integration\Resource\Sku;

use Apiera\Sdk\DTO\Request\Sku\SkuRequest;
use Apiera\Sdk\Enum\LdType;
use Tests\Integration\Resource\AbstractTestDeleteOperation;
use Tests\Integration\Resource\ResourceOperationTrait;

final class DeleteSkuTest extends AbstractTestDeleteOperation
{
    use ResourceOperationTrait;

    protected function getResourcePath(): string
    {
        return '/skus';
    }

    protected function getResourceType(): string
    {
        return LdType::Sku->value;
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     */
    protected function executeDeleteOperation(): void
    {
        $request = new SkuRequest(
            iri: $this->buildUri('skus', $this->resourceId)
        );

        $this->sdk->sku()->delete($request);
    }
}
