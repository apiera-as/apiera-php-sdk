<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Request\Brand;

use Apiera\Sdk\Attribute\RequestField;
use Apiera\Sdk\Attribute\SkipRequest;
use Apiera\Sdk\Interface\DTO\RequestInterface;

/**
 * @author Marie Rinden <marie@shoppingnorge.no>
 * @since 1.0.0
 */
final readonly class BrandRequest implements RequestInterface
{
    /**
     * @param string|null $image File IRI reference
     * @param string|null $store Store IRI reference (required for get collection and create operations)
     * @param string|null $iri Resource IRI reference (required for get, update and delete operations)
     */
    public function __construct(
        #[RequestField('name')]
        private ?string $name = null,
        #[RequestField('description')]
        private ?string $description = null,
        #[RequestField('image')]
        private ?string $image = null,
        #[SkipRequest]
        private ?string $store = null,
        #[SkipRequest]
        private ?string $iri = null
    ) {
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
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
            'image' => $this->image,
        ];
    }
}
