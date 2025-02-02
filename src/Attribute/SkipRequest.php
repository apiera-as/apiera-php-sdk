<?php

declare(strict_types=1);

namespace Apiera\Sdk\Attribute;

use Attribute;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 0.3.0
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
final readonly class SkipRequest
{
}
