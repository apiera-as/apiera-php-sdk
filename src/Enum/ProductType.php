<?php

declare(strict_types=1);

namespace Apiera\Sdk\Enum;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 1.0.0
 */
enum ProductType: string
{
    case Simple = 'simple';
    case Variable = 'variable';
}
