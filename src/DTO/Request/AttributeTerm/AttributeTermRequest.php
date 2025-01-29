<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Request\AttributeTerm;

use Apiera\Sdk\Interface\DTO\RequestInterface;

/**
 * @author Marie Rinden <marie@shoppingnorge.no>
 * @since 0.3.0
 */
final readonly class AttributeTermRequest implements RequestInterface
{
    public function __construct(
        private string $name,
        private ?string $iri = null,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
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
