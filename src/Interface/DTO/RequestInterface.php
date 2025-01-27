<?php

declare(strict_types=1);

namespace Apiera\Sdk\Interface\DTO;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 0.1.0
 */
interface RequestInterface
{
    public function getIri(): ?string;

    /** @return array<string, mixed> */
    public function toArray(): array;
}
