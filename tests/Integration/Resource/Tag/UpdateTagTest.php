<?php

declare(strict_types=1);

namespace Tests\Integration\Resource\Tag;

use Apiera\Sdk\DTO\Request\Tag\TagRequest;
use Apiera\Sdk\DTO\Response\Tag\TagResponse;
use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Interface\DTO\ResponseInterface;
use Tests\Integration\Resource\AbstractTestUpdateOperation;
use Tests\Integration\Resource\StoreScopedOperationTrait;

final class UpdateTagTest extends AbstractTestUpdateOperation
{
    use StoreScopedOperationTrait;

    protected function getStoreScopedResourcePath(): string
    {
        return '/tags';
    }

    protected function getResourceType(): string
    {
        return LdType::Tag->value;
    }

    /**
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     */
    protected function executeUpdateOperation(): TagResponse
    {
        $request = new TagRequest(
            name: 'string',
            iri: $this->buildStoreUri('tags', $this->resourceId),
        );

        return $this->sdk->tag()->update($request);
    }

    /**
     * @return array<string, mixed>
     */
    protected function getMockResponseData(): array
    {
        return [
            '@id' => $this->buildStoreUri('tags', $this->resourceId),
            '@type' => $this->getResourceType(),
            'uuid' => $this->resourceId,
            'createdAt' => self::CREATED_AT,
            'updatedAt' => self::UPDATED_AT,
            'name' => 'string',
            'store' => $this->buildStoreUri(),
        ];
    }

    /**
     * @return class-string<TagResponse>
     */
    protected function getResponseClass(): string
    {
        return TagResponse::class;
    }

    /**
     * @param TagResponse $response
     */
    protected function assertResourceSpecificFields(ResponseInterface $response): void
    {
        $this->assertEquals('string', $response->getName());
        $this->assertEquals($this->buildStoreUri(), $response->getStore());
    }
}
