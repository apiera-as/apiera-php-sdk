<?php

declare(strict_types=1);

namespace Apiera\Sdk\Interface;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 1.0.0
 */
interface ResponseFieldInterface
{
    public function getName(): string;

    public function getTransformerClass(): ?string;
}
