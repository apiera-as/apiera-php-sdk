<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Request\Category;

use Apiera\Sdk\Attribute\RequestField;
use Apiera\Sdk\Attribute\SkipRequest;
use Apiera\Sdk\Interface\DTO\RequestInterface;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 0.1.0
 */
final readonly class CategoryRequest implements RequestInterface
{
    /**
     * @param string $name The category name
     * @param string|null $description The category description
     * @param string|null $parent The category parent iri
     * @param string|null $image The category image iri
     * @param string|null $store The category store iri
     * @param string|null $iri The category iri
     */
    public function __construct(
        #[RequestField('name')]
        private string $name,
        #[RequestField('description')]
        private ?string $description = null,
        #[RequestField('parent')]
        private ?string $parent = null,
        #[RequestField('image')]
        private ?string $image = null,
        #[SkipRequest]
        private ?string $store = null,
        #[SkipRequest]
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

    public function getParent(): ?string
    {
        return $this->parent;
    }

    public function getImage(): ?string
    {
        return $this->image;
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
            'description' => $this->description,
            'parent' => $this->parent,
            'image' => $this->image,
        ];
    }
}
