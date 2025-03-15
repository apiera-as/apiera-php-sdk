<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Request\Integration;

use Apiera\Sdk\Attribute\RequestField;
use Apiera\Sdk\Attribute\SkipRequest;
use Apiera\Sdk\Enum\IntegrationProtocol;
use Apiera\Sdk\Enum\IntegrationStatus;
use Apiera\Sdk\Interface\DTO\RequestInterface;
use Apiera\Sdk\Transformer\IntegrationProtocolTransformer;
use Apiera\Sdk\Transformer\IntegrationStatusTransformer;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 2.1.0
 */
final readonly class IntegrationRequest implements RequestInterface
{
    /**
     * @param string|null $name Integration name
     * @param IntegrationProtocol|null $protocol Integration protocol enumeration
     * @param IntegrationStatus|null $status Integration status enumeration
     * @param string|null $iri Integration IRI reference (required for get, update and delete operations)
     */
    public function __construct(
        #[RequestField('name')]
        private ?string $name = null,
        #[RequestField('protocol', IntegrationProtocolTransformer::class)]
        private ?IntegrationProtocol $protocol = null,
        #[RequestField('status', IntegrationStatusTransformer::class)]
        private ?IntegrationStatus $status = null,
        #[SkipRequest]
        private ?string $iri = null
    ) {
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getProtocol(): ?IntegrationProtocol
    {
        return $this->protocol;
    }

    public function getStatus(): ?IntegrationStatus
    {
        return $this->status;
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
            'name' => $this->name,
            'protocol' => $this->protocol->value ?? null,
            'status' => $this->status->value ?? null,
        ];
    }
}
