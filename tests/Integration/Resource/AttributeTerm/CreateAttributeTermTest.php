<?php

declare(strict_types=1);

namespace Integration\Resource\AttributeTerm;

use Apiera\Sdk\DTO\Request\AttributeTerm\AttributeTermRequest;
use Apiera\Sdk\DTO\Response\AttributeTerm\AttributeTermResponse;
use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Interface\DTO\ResponseInterface;
use Tests\Integration\Resource\AbstractTestCreateOperation;
use Tests\Integration\Resource\StoreScopedOperationTrait;

final class CreateAttributeTermTest extends AbstractTestCreateOperation
{
    use StoreScopedOperationTrait;

    protected function getStoreScopedResourcePath(): string
    {
        return sprintf('/attributes/%s/terms', $this->resourceId);
    }

    protected function getResourceType(): string
    {
        return LdType::AttributeTerm->value;
    }

    /**
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     */
    protected function executeCreateOperation(): AttributeTermResponse
    {
        $request = new AttributeTermRequest(
            name: 'Test term',
            attribute: $this->buildStoreUri('attributes', $this->resourceId),
        );

        return $this->sdk->attributeTerm()->create($request);
    }

    /**
     * @return array<string, mixed>
     */
    protected function getMockResponseData(): array
    {
        return [
            '@id' => $this->buildStoreUri('attributes', $this->resourceId, 'terms', $this->resourceId),
            '@type' => $this->getResourceType(),
            'uuid' => $this->resourceId,
            'createdAt' => self::CREATED_AT,
            'updatedAt' => self::UPDATED_AT,
            'name' => 'Test term',
            'attribute' => $this->buildStoreUri('attributes', $this->resourceId),
            'store' => $this->buildStoreUri(),
        ];
    }

    /**
     * @return class-string<AttributeTermResponse>
     */
    protected function getResponseClass(): string
    {
        return AttributeTermResponse::class;
    }

    /**
     * @param AttributeTermResponse $response
     */
    protected function assertResourceSpecificFields(ResponseInterface $response): void
    {
        $this->assertEquals('Test term', $response->getName());
        $this->assertEquals($this->buildStoreUri('attributes', $this->resourceId), $response->getAttribute());
        $this->assertEquals($this->buildStoreUri(), $response->getStore());
    }
}
