<?php

declare(strict_types=1);

namespace Apiera\Sdk\Attribute;

use Apiera\Sdk\Interface\ResponseFieldInterface;
use Attribute;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 1.0.0
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
final readonly class JsonLdResponseField implements ResponseFieldInterface
{
    /**
     * @param class-string<\Apiera\Sdk\Interface\TransformerInterface>|null $transformerClass
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

    /**
     * @return class-string<\Apiera\Sdk\Interface\TransformerInterface>|null
     */
    public function getTransformerClass(): ?string
    {
        return $this->transformerClass;
    }
}
