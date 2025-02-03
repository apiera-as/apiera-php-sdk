<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Request\File;

use Apiera\Sdk\DTO\Request\File\FileRequest;
use Tests\Unit\DTO\Request\AbstractDTORequest;

final class FileRequestTest extends AbstractDTORequest
{
    protected function getRequestClass(): string
    {
        return FileRequest::class;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getConstructorParams(): array
    {
        return [
            'url' => 'https://example.com/file.pdf',
            'name' => 'Example File',
            'iri' => '/api/v1/files/123',
        ];
    }
}
