<?php

declare(strict_types=1);

namespace Apiera\Sdk\Attribute;

use Apiera\Sdk\Interface\ResponseFieldInterface;
use Apiera\Sdk\Interface\TransformerInterface;
use Attribute;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 0.3.0
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
final readonly class JsonLdResponseField implements ResponseFieldInterface
{
    /**
     * @param class-string<TransformerInterface>|null $transformerClass
     */
    public function __construct(
        private string $name,
        private ?string $transformerClass = null,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTransformerClass(): ?string
    {
        return $this->transformerClass;
    }
}
