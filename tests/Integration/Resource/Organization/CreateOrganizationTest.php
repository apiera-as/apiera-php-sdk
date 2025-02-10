<?php

declare(strict_types=1);

namespace Tests\Integration\Resource\Organization;

use Apiera\Sdk\DTO\Request\Organization\OrganizationRequest;
use Apiera\Sdk\DTO\Response\Organization\OrganizationResponse;
use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Interface\DTO\ResponseInterface;
use Tests\Integration\Resource\AbstractTestCreateOperation;
use Tests\Integration\Resource\ResourceOperationTrait;

final class CreateOrganizationTest extends AbstractTestCreateOperation
{
    use ResourceOperationTrait;

    protected function getResourcePath(): string
    {
        return '/organizations';
    }

    protected function getResourceType(): string
    {
        return LdType::Organization->value;
    }

    /**
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     */
    protected function executeCreateOperation(): OrganizationResponse
    {
        $request = new OrganizationRequest(
            name: 'string',
            extId: 'string',
        );

        return $this->sdk->organization()->create($request);
    }

    /**
     * @return array<string, mixed>
     */
    protected function getMockResponseData(): array
    {
        return [
            '@id' => $this->buildUri('organizations', $this->resourceId),
            '@type' => $this->getResourceType(),
            'uuid' => $this->resourceId,
            'createdAt' => self::CREATED_AT,
            'updatedAt' => self::UPDATED_AT,
            'name' => 'string',
            'extId' => 'string',
        ];
    }

    /**
     * @return class-string<OrganizationResponse>
     */
    protected function getResponseClass(): string
    {
        return OrganizationResponse::class;
    }

    /**
     * @param OrganizationResponse $response
     */
    protected function assertResourceSpecificFields(ResponseInterface $response): void
    {
        $this->assertEquals('string', $response->getName());
        $this->assertEquals('string', $response->getExtId());
    }
}
