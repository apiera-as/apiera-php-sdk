<?php

declare(strict_types=1);

namespace Apiera\Sdk\Interface\DTO;

use Apiera\Sdk\Enum\LdType;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 0.1.0
 */
interface JsonLDInterface
{
    public function getLdId(): string;

    public function getLdType(): LdType;
}
