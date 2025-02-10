<?php

declare(strict_types=1);

namespace Apiera\Sdk\Enum;

/**
 * @author Marie Rinden <marie@shoppingnorge.no>
 * @since 0.3.0
 */
enum VariantStatus: string
{
    case Active = 'active';
    case Inactive = 'inactive';
}
