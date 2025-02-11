<?php

declare(strict_types=1);

namespace Tests\Integration\Resource\Category;

use Apiera\Sdk\DTO\Request\Category\CategoryRequest;
use Apiera\Sdk\DTO\Response\Category\CategoryResponse;
use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Interface\DTO\ResponseInterface;
use Tests\Integration\Resource\AbstractTestGetOperation;
use Tests\Integration\Resource\StoreScopedOperationTrait;

final class GetCategoryTest extends AbstractTestGetOperation
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
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    protected function executeGetOperation(): CategoryResponse
    {
        $request = new CategoryRequest(
            iri: $this->buildStoreUri('categories', $this->resourceId)
        );

        return $this->sdk->category()->get($request);
    }

    /**
     * @return array<string, mixed>
     */
    protected function getMockResponseData(): array
    {
        return [
            '@id' => $this->buildStoreUri('categories', $this->resourceId),
            '@type' => $this->getResourceType(),
            'uuid' => $this->resourceId,
            'createdAt' => self::CREATED_AT,
            'updatedAt' => self::UPDATED_AT,
            'name' => 'Test category',
            'description' => 'Test category description',
            'parent' => $this->buildStoreUri('categories', $this->resourceId),
            'image' => $this->buildUri('files', $this->resourceId),
            'store' => $this->buildStoreUri(),
        ];
    }

    /**
     * @return class-string<CategoryResponse>
     */
    protected function getResponseClass(): string
    {
        return CategoryResponse::class;
    }

    /**
     * @param CategoryResponse $response
     */
    protected function assertResourceSpecificFields(ResponseInterface $response): void
    {
        $this->assertEquals('Test category', $response->getName());
        $this->assertEquals('Test category description', $response->getDescription());
        $this->assertEquals($this->buildStoreUri('categories', $this->resourceId), $response->getParent());
        $this->assertEquals($this->buildUri('files', $this->resourceId), $response->getImage());
        $this->assertEquals($this->buildStoreUri(), $response->getStore());
    }
}
