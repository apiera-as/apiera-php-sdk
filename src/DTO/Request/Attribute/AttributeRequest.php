<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Request\Attribute;

use Apiera\Sdk\Interface\DTO\RequestInterface;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @package Apiera\Sdk\DTO\Request\Attribute
 * @since 0.2.0
 */
final readonly class AttributeRequest implements RequestInterface
{
    /**
     * @param string $name The attribute name
     * @param string|null $store The attribute store iri
     * @param string|null $iri The attribute iri
     */
    public function __construct(
        private string $name,
        private ?string $store = null,
        private ?string $iri = null
    ) {
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getStore(): ?string
    {
        return $this->store;
    }

    /**
     * @return string|null
     */
    public function getIri(): ?string
    {
        return $this->iri;
    }

    /**
     * @return string[]
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
        ];
    }
}
