<?php

declare(strict_types=1);

namespace Tests\Integration\Resource\Inventory;

use Apiera\Sdk\DTO\Request\Inventory\InventoryRequest;
use Apiera\Sdk\Enum\LdType;
use Tests\Integration\Resource\AbstractTestDeleteOperation;
use Tests\Integration\Resource\InventoryLocationScopedTrait;

final class DeleteInventoryTest extends AbstractTestDeleteOperation
{
    use InventoryLocationScopedTrait;

    protected function getInventoryLocationScopedResourcePath(): string
    {
        return '/inventories';
    }

    protected function getResourceType(): string
    {
        return LdType::Inventory->value;
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     */
    protected function executeDeleteOperation(): void
    {
        $request = new InventoryRequest(
            iri: $this->buildIntegrationUri('inventories', $this->resourceId)
        );

        $this->sdk->inventory()->delete($request);
    }
}
