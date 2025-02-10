<?php

declare(strict_types=1);

namespace Tests\Integration\Resource\Organization;

use Apiera\Sdk\DTO\Request\Organization\OrganizationRequest;
use Apiera\Sdk\Enum\LdType;
use Tests\Integration\Resource\AbstractTestDeleteOperation;
use Tests\Integration\Resource\ResourceOperationTrait;

final class DeleteOrganizationTest extends AbstractTestDeleteOperation
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
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     */
    protected function executeDeleteOperation(): void
    {
        $request = new OrganizationRequest(
            iri: $this->buildUri('organizations', $this->resourceId)
        );

        $this->sdk->organization()->delete($request);
    }
}
