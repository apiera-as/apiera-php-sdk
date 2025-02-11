<?php

declare(strict_types=1);

namespace Apiera\Sdk\Enum;

/**
 * @author Marie Rinden <marie@shoppingnorge.no>
 * @since 1.0.0
 */
enum VariantStatus: string
{
    case Active = 'active';
    case Inactive = 'inactive';
}
