<?php

declare(strict_types=1);

namespace Tests\Integration\Resource\Variant;

use Apiera\Sdk\DTO\Request\Variant\VariantRequest;
use Apiera\Sdk\DTO\Response\Variant\VariantResponse;
use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Enum\VariantStatus;
use Apiera\Sdk\Interface\DTO\ResponseInterface;
use Tests\Integration\Resource\AbstractTestGetOperation;
use Tests\Integration\Resource\StoreScopedOperationTrait;

final class GetVariantTest extends AbstractTestGetOperation
{
    use StoreScopedOperationTrait;

    protected function getStoreScopedResourcePath(): string
    {
        return sprintf('/products/%s/variants', $this->resourceId);
    }

    protected function getResourceType(): string
    {
        return LdType::Variant->value;
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    protected function executeGetOperation(): VariantResponse
    {
        $request = new VariantRequest(
            iri: $this->buildStoreUri('products', $this->resourceId, 'variants', $this->resourceId),
        );

        return $this->sdk->variant()->get($request);
    }

    /**
     * @return array<string, mixed>
     */
    protected function getMockResponseData(): array
    {
        return [
            '@id' => $this->buildStoreUri('products', $this->resourceId, 'variants', $this->resourceId),
            '@type' => $this->getResourceType(),
            'uuid' => $this->resourceId,
            'createdAt' => self::CREATED_AT,
            'updatedAt' => self::UPDATED_AT,
            'price' => '100.00',
            'salePrice' => '99.00',
            'description' => 'Test variant description',
            'weight' => '100.00',
            'length' => '100.00',
            'width' => '100.00',
            'height' => '100.00',
            'status' => VariantStatus::Active->value,
            'store' => $this->buildStoreUri(),
            'product' => $this->buildStoreUri('products', $this->resourceId),
            'sku' => $this->buildUri('skus', $this->resourceId),
            'attributeTerms' => [$this->buildStoreUri('attributes', $this->resourceId, 'terms', $this->resourceId)],
            'images' => [$this->buildUri('files', $this->resourceId)],
        ];
    }

    /**
     * @return class-string<VariantResponse>
     */
    protected function getResponseClass(): string
    {
        return VariantResponse::class;
    }

    /**
     * @param VariantResponse $response
     */
    protected function assertResourceSpecificFields(ResponseInterface $response): void
    {
        $this->assertEquals('100.00', $response->getPrice());
        $this->assertEquals('99.00', $response->getSalePrice());
        $this->assertEquals('Test variant description', $response->getDescription());
        $this->assertEquals('100.00', $response->getWeight());
        $this->assertEquals('100.00', $response->getLength());
        $this->assertEquals('100.00', $response->getWidth());
        $this->assertEquals('100.00', $response->getHeight());
        $this->assertEquals(VariantStatus::Active, $response->getStatus());
        $this->assertEquals($this->buildStoreUri(), $response->getStore());
        $this->assertEquals($this->buildStoreUri('products', $this->resourceId), $response->getProduct());
        $this->assertEquals($this->buildUri('skus', $this->resourceId), $response->getSku());
        $this->assertEquals(
            [$this->buildStoreUri('attributes', $this->resourceId, 'terms', $this->resourceId)],
            $response->getAttributeTerms()
        );
        $this->assertEquals([$this->buildUri('files', $this->resourceId)], $response->getImages());
    }
}
