<?php

declare(strict_types=1);

namespace Tests\Integration\Resource\Inventory;

use Apiera\Sdk\DTO\Request\Inventory\InventoryRequest;
use Apiera\Sdk\DTO\Response\Inventory\InventoryResponse;
use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Interface\DTO\ResponseInterface;
use Tests\Integration\Resource\AbstractTestGetOperation;
use Tests\Integration\Resource\InventoryLocationScopedTrait;

final class GetInventoryTest extends AbstractTestGetOperation
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
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    protected function executeGetOperation(): InventoryResponse
    {
        $request = new InventoryRequest(
            iri: $this->buildIntegrationUri('inventories', $this->resourceId)
        );

        return $this->sdk->inventory()->get($request);
    }

    /**
     * @return array<string, mixed>
     */
    protected function getMockResponseData(): array
    {
        return [
            '@id' => $this->buildIntegrationUri('inventories', $this->resourceId),
            '@type' => $this->getResourceType(),
            'uuid' => $this->resourceId,
            'createdAt' => self::CREATED_AT,
            'updatedAt' => self::UPDATED_AT,
            'quantity' => 100,
            'sku' => $this->buildUri('skus', $this->resourceId),
            'inventoryLocation' => $this->buildIntegrationUri(),
        ];
    }

    /**
     * @return class-string<InventoryResponse>
     */
    protected function getResponseClass(): string
    {
        return InventoryResponse::class;
    }

    /**
     * @param InventoryResponse $response
     */
    protected function assertResourceSpecificFields(ResponseInterface $response): void
    {
        $this->assertEquals(100, $response->getQuantity());
        $this->assertEquals($this->buildUri('skus', $this->resourceId), $response->getSku());
        $this->assertEquals($this->buildIntegrationUri(), $response->getInventoryLocation());
    }
}
