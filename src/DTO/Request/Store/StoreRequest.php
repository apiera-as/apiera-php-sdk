<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Request\Store;

use Apiera\Sdk\Attribute\RequestField;
use Apiera\Sdk\Attribute\SkipRequest;
use Apiera\Sdk\Interface\DTO\RequestInterface;

/**
 * @author Marie Rinden <marie@shoppingnorge.no>
 * @since 1.0.0
 */
final readonly class StoreRequest implements RequestInterface
{
    public function __construct(
        #[RequestField('name')]
        private ?string $name = null,
        #[RequestField('description')]
        private ?string $description = null,
        #[RequestField('image')]
        private ?string $image = null,
        #[SkipRequest]
        private ?string $iri = null,
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
