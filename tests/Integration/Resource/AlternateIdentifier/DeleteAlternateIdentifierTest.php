<?php

declare(strict_types=1);

namespace Tests\Integration\Resource\AlternateIdentifier;

use Apiera\Sdk\DTO\Request\AlternateIdentifier\AlternateIdentifierRequest;
use Apiera\Sdk\Enum\LdType;
use Tests\Integration\Resource\AbstractTestDeleteOperation;
use Tests\Integration\Resource\ResourceOperationTrait;

final class DeleteAlternateIdentifierTest extends AbstractTestDeleteOperation
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
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     */
    protected function executeDeleteOperation(): void
    {
        $request = new AlternateIdentifierRequest(
            iri: $this->buildUri('alternate_identifiers', $this->resourceId)
        );

        $this->sdk->alternateIdentifier()->delete($request);
    }
}
