<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Request\File;

use Apiera\Sdk\Attribute\RequestField;
use Apiera\Sdk\Attribute\SkipRequest;
use Apiera\Sdk\Interface\DTO\RequestInterface;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 1.0.0
 */
final readonly class FileRequest implements RequestInterface
{
    /**
     * @param string|null $iri Resource IRI reference (required for get, update and delete operations)
     */
    public function __construct(
        #[RequestField('url')]
        private ?string $url = null,
        #[RequestField('name')]
        private ?string $name = null,
        #[SkipRequest]
        private ?string $iri = null
    ) {
    }

    public function getUrl(): ?string
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
