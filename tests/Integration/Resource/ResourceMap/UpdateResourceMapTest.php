<?php

declare(strict_types=1);

namespace Tests\Integration\Resource\ResourceMap;

use Apiera\Sdk\DTO\Request\ResourceMap\ResourceMapRequest;
use Apiera\Sdk\DTO\Response\ResourceMap\ResourceMapResponse;
use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Interface\DTO\ResponseInterface;
use Tests\Integration\Resource\AbstractTestUpdateOperation;
use Tests\Integration\Resource\IntegrationScopedOperationTrait;

final class UpdateResourceMapTest extends AbstractTestUpdateOperation
{
    use IntegrationScopedOperationTrait;

    protected function getIntegrationScopedResourcePath(): string
    {
        return '/mappings';
    }

    protected function getResourceType(): string
    {
        return LdType::IntegrationResourceMap->value;
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    protected function executeUpdateOperation(): ResourceMapResponse
    {
        $request = new ResourceMapRequest(
            external: 'string',
            resource: 'string',
            iri: $this->buildIntegrationUri('mappings', $this->resourceId),
        );

        return $this->sdk->resourceMap()->update($request);
    }

    /**
     * @return array<string, mixed>
     */
    protected function getMockResponseData(): array
    {
        return [
            '@id' => $this->buildIntegrationUri('mappings', $this->resourceId),
            '@type' => $this->getResourceType(),
            'uuid' => $this->resourceId,
            'createdAt' => self::CREATED_AT,
            'updatedAt' => self::UPDATED_AT,
            'external' => 'string',
            'internal' => 'string',
            'resource' => 'string',
            'resourceType' => 'string',
            'integration' => '/api/v1/integrations/123',
        ];
    }

    /**
     * @return class-string<ResourceMapResponse>
     */
    protected function getResponseClass(): string
    {
        return ResourceMapResponse::class;
    }

    /**
     * @param ResourceMapResponse $response
     */
    protected function assertResourceSpecificFields(ResponseInterface $response): void
    {
        $this->assertEquals('string', $response->getExternal());
        $this->assertEquals('string', $response->getInternal());
        $this->assertEquals('string', $response->getResource());
        $this->assertEquals('string', $response->getResourceType());
        $this->assertEquals('/api/v1/integrations/123', $response->getIntegration());
    }
}
