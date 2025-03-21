<?php

declare(strict_types=1);

namespace Tests\Integration\Resource\Distributor;

use Apiera\Sdk\DTO\Request\Distributor\DistributorRequest;
use Apiera\Sdk\DTO\Response\Distributor\DistributorResponse;
use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Interface\DTO\ResponseInterface;
use Tests\Integration\Resource\AbstractTestCreateOperation;
use Tests\Integration\Resource\StoreScopedOperationTrait;

final class CreateDistributorTest extends AbstractTestCreateOperation
{
    use StoreScopedOperationTrait;

    protected function getStoreScopedResourcePath(): string
    {
        return '/distributors';
    }

    protected function getResourceType(): string
    {
        return LdType::Distributor->value;
    }

    /**
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     */
    protected function executeCreateOperation(): DistributorResponse
    {
        $request = new DistributorRequest(
            name: 'Test distributor',
            store: $this->buildStoreUri()
        );

        return $this->sdk->distributor()->create($request);
    }

    /**
     * @return array<string, mixed>
     */
    protected function getMockResponseData(): array
    {
        return [
            '@id' => $this->buildStoreUri('distributors', $this->resourceId),
            '@type' => $this->getResourceType(),
            'uuid' => $this->resourceId,
            'createdAt' => self::CREATED_AT,
            'updatedAt' => self::UPDATED_AT,
            'name' => 'Test distributor',
            'store' => $this->buildStoreUri(),
        ];
    }

    /**
     * @return class-string<DistributorResponse>
     */
    protected function getResponseClass(): string
    {
        return DistributorResponse::class;
    }

    /**
     * @param DistributorResponse $response
     */
    protected function assertResourceSpecificFields(ResponseInterface $response): void
    {
        $this->assertEquals('Test distributor', $response->getName());
        $this->assertEquals($this->buildStoreUri(), $response->getStore());
    }
}
