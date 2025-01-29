<?php

namespace Apiera\Sdk\DTO\Request\Brand;

use Apiera\Sdk\Interface\DTO\RequestInterface;

/**
 * @author Marie Rinden <marie@shoppingnorge.no>
 * @since 0.3.0
 */
final readonly class BrandRequest implements RequestInterface
{
    /**
     * @param string $name
     * @param string $description
     * @param string|null $iri
     */
    public function __construct(
        private string $name,
        private string $description,
        private ?string $iri = null
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
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
          'description' => $this->description,
        ];
    }
}