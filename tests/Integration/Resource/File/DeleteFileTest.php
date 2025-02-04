<?php

declare(strict_types=1);

namespace Integration\Resource\File;

use Apiera\Sdk\DTO\Request\File\FileRequest;
use Apiera\Sdk\Enum\LdType;
use Tests\Integration\Resource\AbstractTestDeleteOperation;
use Tests\Integration\Resource\ResourceOperationTrait;

final class DeleteFileTest extends AbstractTestDeleteOperation
{
    use ResourceOperationTrait;

    protected function getResourcePath(): string
    {
        return '/files';
    }

    protected function getResourceType(): string
    {
        return LdType::File->value;
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     */
    protected function executeDeleteOperation(): void
    {
        $request = new FileRequest(
            iri: $this->buildUri('files', $this->resourceId)
        );

        $this->sdk->file()->delete($request);
    }
}
