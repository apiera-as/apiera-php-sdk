<?php

declare(strict_types=1);

namespace Tests\Integration\Resource\Attribute;

use Apiera\Sdk\DTO\Request\Attribute\AttributeRequest;
use Apiera\Sdk\DTO\Response\Attribute\AttributeResponse;
use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Interface\DTO\ResponseInterface;
use Tests\Integration\Resource\AbstractTestUpdateOperation;
use Tests\Integration\Resource\StoreScopedOperationTrait;

final class UpdateAttributeTest extends AbstractTestUpdateOperation
{
    use StoreScopedOperationTrait;

    protected function getStoreScopedResourcePath(): string
    {
        return '/attributes';
    }

    protected function getResourceType(): string
    {
        return LdType::Attribute->value;
    }

    /**
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     */
    protected function executeUpdateOperation(): AttributeResponse
    {
        $request = new AttributeRequest(
            name: 'Test attribute',
            iri: $this->buildStoreUri('attributes', $this->resourceId),
        );

        return $this->sdk->attribute()->update($request);
    }

    /**
     * @return array<string, mixed>
     */
    protected function getMockResponseData(): array
    {
        return [
            '@id' => $this->buildStoreUri('attributes', $this->resourceId),
            '@type' => $this->getResourceType(),
            'uuid' => $this->resourceId,
            'createdAt' => self::CREATED_AT,
            'updatedAt' => self::UPDATED_AT,
            'name' => 'Test attribute',
            'store' => $this->buildStoreUri(),
        ];
    }

    /**
     * @return class-string<AttributeResponse>
     */
    protected function getResponseClass(): string
    {
        return AttributeResponse::class;
    }

    /**
     * @param AttributeResponse $response
     */
    protected function assertResourceSpecificFields(ResponseInterface $response): void
    {
        $this->assertEquals('Test attribute', $response->getName());
        $this->assertEquals($this->buildStoreUri(), $response->getStore());
    }
}
