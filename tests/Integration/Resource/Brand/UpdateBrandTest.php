<?php

declare(strict_types=1);

namespace Tests\Integration\Resource\Brand;

use Apiera\Sdk\DTO\Request\Brand\BrandRequest;
use Apiera\Sdk\DTO\Response\Brand\BrandResponse;
use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Interface\DTO\ResponseInterface;
use Tests\Integration\Resource\AbstractTestUpdateOperation;
use Tests\Integration\Resource\StoreScopedOperationTrait;

final class UpdateBrandTest extends AbstractTestUpdateOperation
{
    use StoreScopedOperationTrait;

    protected function getStoreScopedResourcePath(): string
    {
        return '/brands';
    }

    protected function getResourceType(): string
    {
        return LdType::Brand->value;
    }

    /**
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     */
    protected function executeUpdateOperation(): BrandResponse
    {
        $request = new BrandRequest(
            name: 'Test brand',
            description: 'Test brand description',
            image: $this->buildUri('files', $this->resourceId),
            iri: $this->buildStoreUri('brands', $this->resourceId),
        );

        return $this->sdk->brand()->update($request);
    }

    /**
     * @return array<string, mixed>
     */
    protected function getMockResponseData(): array
    {
        return [
            '@id' => $this->buildStoreUri('brands', $this->resourceId),
            '@type' => $this->getResourceType(),
            'uuid' => $this->resourceId,
            'createdAt' => self::CREATED_AT,
            'updatedAt' => self::UPDATED_AT,
            'name' => 'Test brand',
            'description' => 'Test brand description',
            'image' => $this->buildUri('files', $this->resourceId),
            'store' => $this->buildStoreUri(),
        ];
    }

    /**
     * @return class-string<BrandResponse>
     */
    protected function getResponseClass(): string
    {
        return BrandResponse::class;
    }

    /**
     * @param BrandResponse $response
     */
    protected function assertResourceSpecificFields(ResponseInterface $response): void
    {
        $this->assertEquals('Test brand', $response->getName());
        $this->assertEquals('Test brand description', $response->getDescription());
        $this->assertEquals($this->buildUri('files', $this->resourceId), $response->getImage());
        $this->assertEquals($this->buildStoreUri(), $response->getStore());
    }
}
