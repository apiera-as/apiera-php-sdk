<?php

declare(strict_types=1);

namespace Apiera\Sdk\Enum;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @package Apiera\Sdk\Enum
 * @since 0.1.0
 */
enum HttpHeaders: string
{
    case BaseUrl = 'base_uri';
    case UserAgent = 'User-Agent';
    case ContentType = 'Content-Type';
    case Authorization = 'Authorization';
}
