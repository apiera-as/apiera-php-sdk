<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Request\AlternateIdentifier;

use Apiera\Sdk\Interface\DTO\RequestInterface;

/**
 * @author Marie Rinden <marie@shoppingnorge.no>
 * @package Apiera\Sdk\DTO\Request\AlternateIdentifier
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
        private string $code,
        private string $type,
        private ?string $iri = null,
    ) {
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string|null
     */
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
