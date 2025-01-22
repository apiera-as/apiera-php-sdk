<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Request\Category;

use Apiera\Sdk\Interface\DTO\RequestInterface;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @package Apiera\Sdk\DTO\Request\Category
 * @since 1.0.0
 */
final readonly class CategoryRequest implements RequestInterface
{
    /**
     * @param string $name The category name
     * @param string|null $store The category store iri
     * @param string|null $description The category description
     * @param string|null $parent The category parent iri
     * @param string|null $image The category image iri
     */
    public function __construct(
        private string $name,
        private ?string $store = null,
        private ?string $description = null,
        private ?string $parent = null,
        private ?string $image = null,
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
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return string|null
     */
    public function getParent(): ?string
    {
        return $this->parent;
    }

    /**
     * @return string|null
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * @return string|null
     */
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
            'parent' => $this->parent,
            'image' => $this->image,
        ];
    }
}
