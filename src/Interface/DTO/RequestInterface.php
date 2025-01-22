<?php

declare(strict_types=1);

namespace Apiera\Sdk\Interface\DTO;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @package Apiera\Sdk\Interface\DTO
 * @since 0.1.0
 */
interface RequestInterface
{
    /**
     * @return string|null
     */
    public function getIri(): ?string;

    /** @return array<string, mixed> */
    public function toArray(): array;
}
