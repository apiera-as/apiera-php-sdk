<?php

declare(strict_types=1);

namespace Apiera\Sdk\Interface;

use Psr\Cache\CacheItemPoolInterface;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @package Apiera\Sdk\Interface
 * @since 0.1.0
 */
interface ConfigurationInterface
{
    /**
     * @return string
     */
    public function getBaseUrl(): string;

    /**
     * @return int
     */
    public function getTimeout(): int;

    /**
     * @return string
     */
    public function getUserAgent(): string;

    /**
     * @return bool
     */
    public function getDebugMode(): bool;

    /**
     * @return string
     */
    public function getOauthDomain(): string;

    /**
     * @return string
     */
    public function getOauthClientId(): string;

    /**
     * @return string
     */
    public function getOauthClientSecret(): string;

    /**
     * @return string
     */
    public function getOauthCookieSecret(): string;

    /**
     * @return string
     */
    public function getOauthAudience(): string;

    /**
     * @return string
     */
    public function getOauthOrganizationId(): string;

    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array;

    /**
     * @return CacheItemPoolInterface
     */
    public function getCache(): CacheItemPoolInterface;
}
