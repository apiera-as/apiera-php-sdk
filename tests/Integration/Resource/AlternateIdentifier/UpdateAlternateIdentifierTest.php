<?php

declare(strict_types=1);

namespace Tests\Integration\Resource\AlternateIdentifier;

use Apiera\Sdk\DTO\Request\AlternateIdentifier\AlternateIdentifierRequest;
use Apiera\Sdk\DTO\Response\AlternateIdentifier\AlternateIdentifierResponse;
use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Interface\DTO\ResponseInterface;
use Tests\Integration\Resource\AbstractTestUpdateOperation;
use Tests\Integration\Resource\ResourceOperationTrait;

final class UpdateAlternateIdentifierTest extends AbstractTestUpdateOperation
{
    use ResourceOperationTrait;

    protected function getResourcePath(): string
    {
        return '/alternate_identifiers';
    }

    protected function getResourceType(): string
    {
        return LdType::AlternateIdentifier->value;
    }

    /**
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     */
    protected function executeUpdateOperation(): AlternateIdentifierResponse
    {
        $request = new AlternateIdentifierRequest(
            code: 'ABC123',
            type: 'gtin',
            iri: $this->buildUri('alternate_identifiers', $this->resourceId)
        );

        return $this->sdk->alternateIdentifier()->update($request);
    }

    /**
     * @return array<string, mixed>
     */
    protected function getMockResponseData(): array
    {
        return [
            '@id' => $this->buildUri('alternate_identifiers', $this->resourceId),
            '@type' => $this->getResourceType(),
            'uuid' => $this->resourceId,
            'createdAt' => self::CREATED_AT,
            'updatedAt' => self::UPDATED_AT,
            'type' => 'gtin',
            'code' => 'ABC123',
        ];
    }

    /**
     * @return class-string<AlternateIdentifierResponse>
     */
    protected function getResponseClass(): string
    {
        return AlternateIdentifierResponse::class;
    }

    /**
     * @param AlternateIdentifierResponse $response
     */
    protected function assertResourceSpecificFields(ResponseInterface $response): void
    {
        $this->assertEquals('ABC123', $response->getCode());
        $this->assertEquals('gtin', $response->getType());
    }
}
