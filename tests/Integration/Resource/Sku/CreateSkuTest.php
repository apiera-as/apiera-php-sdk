<?php

declare(strict_types=1);

namespace Tests\Integration\Resource\Sku;

use Apiera\Sdk\DTO\Request\Sku\SkuRequest;
use Apiera\Sdk\DTO\Response\Sku\SkuResponse;
use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Interface\DTO\ResponseInterface;
use Tests\Integration\Resource\AbstractTestCreateOperation;
use Tests\Integration\Resource\ResourceOperationTrait;

final class CreateSkuTest extends AbstractTestCreateOperation
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
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     */
    protected function executeCreateOperation(): SkuResponse
    {
        $request = new SkuRequest(
            code: 'string'
        );

        return $this->sdk->sku()->create($request);
    }

    /**
     * @return array<string, mixed>
     */
    protected function getMockResponseData(): array
    {
        return [
            '@id' => $this->buildUri('skus', $this->resourceId),
            '@type' => $this->getResourceType(),
            'uuid' => $this->resourceId,
            'createdAt' => self::CREATED_AT,
            'updatedAt' => self::UPDATED_AT,
            'code' => 'string',
            'products' => [],
            'variants' => [],
            'inventories' => [],
        ];
    }

    /**
     * @return class-string<SkuResponse>
     */
    protected function getResponseClass(): string
    {
        return SkuResponse::class;
    }

    /**
     * @param SkuResponse $response
     */
    protected function assertResourceSpecificFields(ResponseInterface $response): void
    {
        $this->assertEquals('string', $response->getCode());
        $this->assertIsArray($response->getProducts());
        $this->assertIsArray($response->getVariants());
        $this->assertIsArray($response->getInventories());
    }
}
