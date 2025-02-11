<?php

declare(strict_types=1);

namespace Apiera\Sdk\Enum;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 1.0.0
 */
enum ProductStatus: string
{
    case Active = 'active';
    case Inactive = 'inactive';
}
