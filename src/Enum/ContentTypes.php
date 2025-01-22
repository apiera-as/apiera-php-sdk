<?php

declare(strict_types=1);

namespace Apiera\Sdk\Enum;

enum ContentTypes: string
{
    case LD_JSON = 'application/ld+json';
    case MERGE_PATCH = 'application/merge-patch+json';
}
