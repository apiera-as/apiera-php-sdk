<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Request\File;

use Apiera\Sdk\Interface\DTO\RequestInterface;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 0.3.0
 */
final readonly class FileRequest implements RequestInterface
{
    /**
     * @param string $url The file url
     * @param string|null $name The file name
     * @param string|null $iri The file iri
     */
    public function __construct(
        private string $url,
        private ?string $name = null,
        private ?string $iri = null
    ) {
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getIri(): ?string
    {
        return $this->iri;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'url' => $this->url,
            'name' => $this->name,
        ];
    }
}
