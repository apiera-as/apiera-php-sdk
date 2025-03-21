<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Request\Organization;

use Apiera\Sdk\Attribute\RequestField;
use Apiera\Sdk\Attribute\SkipRequest;
use Apiera\Sdk\Interface\DTO\RequestInterface;

/**
 * @author Marie Rinden <marie@shoppingnorge.no>
 * @since 1.0.0
 */
final readonly class OrganizationRequest implements RequestInterface
{
    public function __construct(
        #[RequestField('name')]
        private ?string $name = null,
        #[RequestField('extId')]
        private ?string $extId = null,
        #[SkipRequest]
        private ?string $iri = null
    ) {
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getExtId(): ?string
    {
        return $this->extId;
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
        ];
    }
}
