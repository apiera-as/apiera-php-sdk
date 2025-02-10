<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Request\PropertyTerm;

use Apiera\Sdk\Attribute\RequestField;
use Apiera\Sdk\Attribute\SkipRequest;
use Apiera\Sdk\Interface\DTO\RequestInterface;

/**
 * @author Marie Rinden <marie@shoppingnorge.no>
 * @since 0.3.0
 */
final readonly class PropertyTermRequest implements RequestInterface
{
    public function __construct(
        #[RequestField('name')]
        private string $name,
        #[RequestField('property')]
        private string $property,
        #[SkipRequest]
        private ?string $iri = null
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getProperty(): string
    {
        return $this->property;
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
            'property' => $this->property,
        ];
    }
}
