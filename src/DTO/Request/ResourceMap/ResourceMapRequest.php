<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Request\ResourceMap;

use Apiera\Sdk\Attribute\RequestField;
use Apiera\Sdk\Attribute\SkipRequest;
use Apiera\Sdk\Interface\DTO\RequestInterface;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 1.0.0
 */
final readonly class ResourceMapRequest implements RequestInterface
{
    public function __construct(
        #[RequestField('external')]
        private ?string $external = null,
        #[RequestField('resource')]
        private ?string $resource = null,
        #[SkipRequest]
        private ?string $integration = null,
        #[SkipRequest]
        private ?string $iri = null,
    ) {
    }

    public function getExternal(): ?string
    {
        return $this->external;
    }

    public function getResource(): ?string
    {
        return $this->resource;
    }

    public function getIntegration(): ?string
    {
        return $this->integration;
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
            'external' => $this->external,
            'resource' => $this->resource,
        ];
    }
}
