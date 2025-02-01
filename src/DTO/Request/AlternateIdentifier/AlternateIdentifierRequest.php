<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Request\AlternateIdentifier;

use Apiera\Sdk\Attribute\RequestField;
use Apiera\Sdk\Attribute\SkipRequest;
use Apiera\Sdk\Interface\DTO\RequestInterface;

/**
 * @author Marie Rinden <marie@shoppingnorge.no>
 * @since 0.2.0
 */
final readonly class AlternateIdentifierRequest implements RequestInterface
{
    /**
     * @param string $code The alternate identifier code
     * @param string $type The alternate identifier type
     * @param string|null $iri The alternate identifier iri
     */
    public function __construct(
        #[RequestField('code')]
        private string $code,
        #[RequestField('type')]
        private string $type,
        #[SkipRequest]
        private ?string $iri = null,
    ) {
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getType(): string
    {
        return $this->type;
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
            'code' => $this->code,
            'type' => $this->type,
        ];
    }
}
