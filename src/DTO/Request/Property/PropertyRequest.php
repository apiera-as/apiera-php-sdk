<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Request\Property;

use Apiera\Sdk\Interface\DTO\RequestInterface;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 0.3.0
 */
final readonly class PropertyRequest implements RequestInterface
{
    /**
     * @param string $name The property name
     * @param string|null $store The property store iri
     * @param string|null $iri The property iri
     */
    public function __construct(
        private string $name,
        private ?string $store = null,
        private ?string $iri = null
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getStore(): ?string
    {
        return $this->store;
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
        ];
    }
}
