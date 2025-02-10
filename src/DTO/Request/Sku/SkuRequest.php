<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Request\Sku;

use Apiera\Sdk\Attribute\RequestField;
use Apiera\Sdk\Attribute\SkipRequest;
use Apiera\Sdk\Interface\DTO\RequestInterface;

/**
 * @author Marie Rinden <marie@shoppingnorge.no>
 * @since 0.3.0
 */
final readonly class SkuRequest implements RequestInterface
{
    public function __construct(
        #[RequestField('code')]
        private ?string $code = null,
        #[SkipRequest]
        private ?string $iri = null
    ) {
    }

    public function getCode(): ?string
    {
        return $this->code;
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
        ];
    }
}
