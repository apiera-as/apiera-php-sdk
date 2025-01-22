<?php

declare(strict_types=1);

namespace Apiera\Sdk\Interface\DTO;

use Apiera\Sdk\Enum\LdType;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @package Apiera\Sdk\Interface\DTO
 * @since 1.0.0
 */
interface JsonLDInterface
{
    /**
     * @return string
     */
    public function getLdId(): string;

    /**
     * @return LdType
     */
    public function getLdType(): LdType;
}
