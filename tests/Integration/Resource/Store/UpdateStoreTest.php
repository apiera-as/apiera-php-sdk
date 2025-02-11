<?php

declare(strict_types=1);

namespace Tests\Integration\Resource\Store;

use Apiera\Sdk\DTO\Request\Store\StoreRequest;
use Apiera\Sdk\DTO\Response\Store\StoreResponse;
use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Interface\DTO\ResponseInterface;
use Tests\Integration\Resource\AbstractTestUpdateOperation;
use Tests\Integration\Resource\ResourceOperationTrait;

final class UpdateStoreTest extends AbstractTestUpdateOperation
{
    use ResourceOperationTrait;

    protected function getResourcePath(): string
    {
        return '/stores';
    }

    protected function getResourceType(): string
    {
        return LdType::Store->value;
    }

    /**
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     */
    protected function executeUpdateOperation(): StoreResponse
    {
        $request = new StoreRequest(
            name: 'string',
            description: 'string',
            image: '/api/v1/files/123',
            iri: $this->buildUri('stores', $this->resourceId)
        );

        return $this->sdk->store()->update($request);
    }

    /**
     * @return array<string, mixed>
     */
    protected function getMockResponseData(): array
    {
        return [
            '@id' => $this->buildUri('stores', $this->resourceId),
            '@type' => $this->getResourceType(),
            'uuid' => $this->resourceId,
            'createdAt' => self::CREATED_AT,
            'updatedAt' => self::UPDATED_AT,
            'name' => 'string',
            'description' => 'string',
            'image' => '/api/v1/files/123',
        ];
    }

    /**
     * @return class-string<StoreResponse>
     */
    protected function getResponseClass(): string
    {
        return StoreResponse::class;
    }

    /**
     * @param StoreResponse $response
     */
    protected function assertResourceSpecificFields(ResponseInterface $response): void
    {
        $this->assertEquals('string', $response->getName());
        $this->assertEquals('string', $response->getDescription());
        $this->assertEquals('/api/v1/files/123', $response->getImage());
    }
}
