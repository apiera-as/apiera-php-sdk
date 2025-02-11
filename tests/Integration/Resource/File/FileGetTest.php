<?php

declare(strict_types=1);

namespace Tests\Integration\Resource\File;

use Apiera\Sdk\DTO\Request\File\FileRequest;
use Apiera\Sdk\DTO\Response\File\FileResponse;
use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Interface\DTO\ResponseInterface;
use Tests\Integration\Resource\AbstractTestGetOperation;
use Tests\Integration\Resource\ResourceOperationTrait;

final class FileGetTest extends AbstractTestGetOperation
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
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    protected function executeGetOperation(): FileResponse
    {
        $request = new FileRequest(
            iri: $this->buildUri('files', $this->resourceId)
        );

        return $this->sdk->file()->get($request);
    }

    /**
     * @return array<string, mixed>
     */
    protected function getMockResponseData(): array
    {
        return [
            '@id' => $this->buildUri('files', $this->resourceId),
            '@type' => $this->getResourceType(),
            'uuid' => $this->resourceId,
            'createdAt' => self::CREATED_AT,
            'updatedAt' => self::UPDATED_AT,
            'url' => 'https://some-s3-url.com/test.pdf',
            'name' => 'Test file',
        ];
    }

    /**
     * @return class-string<FileResponse>
     */
    protected function getResponseClass(): string
    {
        return FileResponse::class;
    }

    /**
     * @param FileResponse $response
     */
    protected function assertResourceSpecificFields(ResponseInterface $response): void
    {
        $this->assertEquals('https://some-s3-url.com/test.pdf', $response->getUrl());
        $this->assertEquals('Test file', $response->getName());
    }
}
