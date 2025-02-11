<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Request\Tag;

use Apiera\Sdk\Attribute\RequestField;
use Apiera\Sdk\Attribute\SkipRequest;
use Apiera\Sdk\Interface\DTO\RequestInterface;

/**
 * @author Marie Rinden <marie@shoppingnorge.no>
 * @since 1.0.0
 */
final readonly class TagRequest implements RequestInterface
{
    public function __construct(
        #[RequestField('name')]
        private ?string $name = null,
        #[RequestField('store')]
        private ?string $store = null,
        #[SkipRequest]
        private ?string $iri = null
    ) {
    }

    public function getName(): ?string
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
