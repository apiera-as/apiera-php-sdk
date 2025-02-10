<?php

declare(strict_types=1);

namespace Tests\Integration\Resource\PropertyTerm;

use Apiera\Sdk\DTO\Request\PropertyTerm\PropertyTermRequest;
use Apiera\Sdk\DTO\Response\PropertyTerm\PropertyTermResponse;
use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Interface\DTO\ResponseInterface;
use Tests\Integration\Resource\AbstractTestUpdateOperation;
use Tests\Integration\Resource\StoreScopedOperationTrait;

final class UpdatePropertyTermTest extends AbstractTestUpdateOperation
{
    use StoreScopedOperationTrait;

    protected function getStoreScopedResourcePath(): string
    {
        return sprintf('/properties/%s/terms', $this->resourceId);
    }

    protected function getResourceType(): string
    {
        return LdType::PropertyTerm->value;
    }

    /**
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     */
    protected function executeUpdateOperation(): PropertyTermResponse
    {
        $request = new PropertyTermRequest(
            name: 'string',
            iri: $this->buildStoreUri('properties', $this->resourceId, 'terms', $this->resourceId),
        );

        return $this->sdk->propertyTerm()->update($request);
    }

    /**
     * @return array<string, mixed>
     */
    protected function getMockResponseData(): array
    {
        return [
            '@id' => $this->buildStoreUri('properties', $this->resourceId, 'terms', $this->resourceId),
            '@type' => $this->getResourceType(),
            'uuid' => $this->resourceId,
            'createdAt' => self::CREATED_AT,
            'updatedAt' => self::UPDATED_AT,
            'name' => 'string',
            'property' => $this->buildStoreUri('properties', $this->resourceId),
            'store' => $this->buildStoreUri(),
        ];
    }

    /**
     * @return class-string<PropertyTermResponse>
     */
    protected function getResponseClass(): string
    {
        return PropertyTermResponse::class;
    }

    /**
     * @param PropertyTermResponse $response
     */
    protected function assertResourceSpecificFields(ResponseInterface $response): void
    {
        $this->assertEquals('string', $response->getName());
        $this->assertEquals($this->buildStoreUri('properties', $this->resourceId), $response->getProperty());
        $this->assertEquals($this->buildStoreUri(), $response->getStore());
    }
}
