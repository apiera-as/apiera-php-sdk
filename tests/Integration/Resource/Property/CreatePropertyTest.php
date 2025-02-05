<?php

declare(strict_types=1);

namespace Tests\Integration\Resource\Property;

use Apiera\Sdk\DTO\Request\Property\PropertyRequest;
use Apiera\Sdk\DTO\Response\Property\PropertyResponse;
use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Interface\DTO\ResponseInterface;
use Tests\Integration\Resource\AbstractTestCreateOperation;
use Tests\Integration\Resource\StoreScopedOperationTrait;

final class CreatePropertyTest extends AbstractTestCreateOperation
{
    use StoreScopedOperationTrait;

    protected function getStoreScopedResourcePath(): string
    {
        return '/properties';
    }

    protected function getResourceType(): string
    {
        return LdType::Property->value;
    }

    /**
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     */
    protected function executeCreateOperation(): PropertyResponse
    {
        $request = new PropertyRequest(
            name: 'Test property',
            store: $this->buildStoreUri()
        );

        return $this->sdk->property()->create($request);
    }

    /**
     * @return array<string, mixed>
     */
    protected function getMockResponseData(): array
    {
        return [
            '@id' => $this->buildStoreUri('properties', $this->resourceId),
            '@type' => $this->getResourceType(),
            'uuid' => $this->resourceId,
            'createdAt' => self::CREATED_AT,
            'updatedAt' => self::UPDATED_AT,
            'name' => 'Test property',
            'store' => $this->buildStoreUri(),
        ];
    }

    /**
     * @return class-string<PropertyResponse>
     */
    protected function getResponseClass(): string
    {
        return PropertyResponse::class;
    }

    /**
     * @param PropertyResponse $response
     */
    protected function assertResourceSpecificFields(ResponseInterface $response): void
    {
        $this->assertEquals('Test property', $response->getName());
        $this->assertEquals($this->buildStoreUri(), $response->getStore());
    }
}
