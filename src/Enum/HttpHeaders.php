<?php

declare(strict_types=1);

namespace Apiera\Sdk\Enum;

enum HttpHeaders: string
{
    case BaseUrl = 'base_uri';
    case UserAgent = 'User-Agent';
    case ContentType = 'Content-Type';
    case Authorization = 'Authorization';
}
