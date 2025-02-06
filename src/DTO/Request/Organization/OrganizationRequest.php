<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Request\Organization;

use Apiera\Sdk\Attribute\RequestField;
use Apiera\Sdk\Attribute\SkipRequest;
use Apiera\Sdk\Interface\DTO\RequestInterface;

/**
 * @author Marie Rinden <marie@shoppingnorge.no>
 * @since 0.3.0
 */
final readonly class OrganizationRequest implements RequestInterface
{
    /**
     * @param string[] $alternateIdentifiers
     */
    public function __construct(
        #[RequestField('name')]
        private string $name,
        #[RequestField('extId')]
        private string $extId,
        #[RequestField('alternateIdentifiers')]
        private array $alternateIdentifiers = [],
        #[SkipRequest]
        private ?string $iri = null
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getExtId(): string
    {
        return $this->extId;
    }

    /**
     * @return string[]
     */
    public function getAlternateIdentifiers(): array
    {
        return $this->alternateIdentifiers;
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
            'extId' => $this->extId,
            'alternateIdentifiers' => $this->alternateIdentifiers,
        ];
    }
}
