<?php

declare(strict_types=1);

namespace Apiera\Sdk\Interface;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 0.3.0
 */
interface RequestFieldInterface
{
    public function getName(): string;

    public function getTransformerClass(): ?string;
}
