<?php

declare(strict_types=1);

namespace Apiera\Sdk\Enum;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 2.1.0
 */
enum IntegrationStatus: string
{
    case Active = 'active';
    case Inactive = 'inactive';
}
