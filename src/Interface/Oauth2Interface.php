<?php

declare(strict_types=1);

namespace Apiera\Sdk\Interface;

use DateTimeInterface;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @package Apiera\Sdk\Interface
 * @since 1.0.0
 */
interface Oauth2Interface
{
    /**
     * @return string
     * @throws ClientExceptionInterface
     */
    public function getAccessToken(): string;

    /**
     * @param string $token
     * @return DateTimeInterface
     */
    public function getTokenExpiration(string $token): DateTimeInterface;
}
