<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Request\AttributeTerm;

use Apiera\Sdk\Attribute\RequestField;
use Apiera\Sdk\Attribute\SkipRequest;
use Apiera\Sdk\Interface\DTO\RequestInterface;

/**
 * @author Marie Rinden <marie@shoppingnorge.no>
 * @since 0.3.0
 */
final readonly class AttributeTermRequest implements RequestInterface
{
    /**
     * @param string|null $attribute IRI reference (required for get collection and create operations)
     * @param string|null $iri IRI reference (required for get, update and delete operations)
     */
    public function __construct(
        #[RequestField('name')]
        private ?string $name = null,
        #[SkipRequest]
        private ?string $attribute = null,
        #[SkipRequest]
        private ?string $iri = null,
    ) {
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getAttribute(): ?string
    {
        return $this->attribute;
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
