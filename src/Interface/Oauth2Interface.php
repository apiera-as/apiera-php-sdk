<?php

declare(strict_types=1);

namespace Apiera\Sdk\Interface;

use DateTimeInterface;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 0.1.0
 */
interface Oauth2Interface
{
    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\CacheException
     * @throws \Apiera\Sdk\Exception\ConfigurationException
     */
    public function getAccessToken(): string;

    public function getTokenExpiration(string $token): DateTimeInterface;
}
