<?php

declare(strict_types=1);

namespace Apiera\Sdk\Enum;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 0.1.0
 */
enum ContentTypes: string
{
    case JsonLD = 'application/ld+json';
    case MergePatch = 'application/merge-patch+json';
}
