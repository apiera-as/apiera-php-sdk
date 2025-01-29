<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Request\Brand;

use Apiera\Sdk\Interface\DTO\RequestInterface;

/**
 * @author Marie Rinden <marie@shoppingnorge.no>
 * @since 0.3.0
 */
final readonly class BrandRequest implements RequestInterface
{
    public function __construct(
        private string $name,
        private ?string $description = null,
        private ?string $store = null,
        private ?string $image = null,
        private ?string $iri = null
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getStore(): ?string
    {
        return $this->store;
    }

    public function getImage(): ?string
    {
        return $this->image;
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
          'image' => $this->image,
        ];
    }
}
