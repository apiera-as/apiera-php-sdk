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
    public function getAuthDomain(): string;

    /**
     * @return string
     */
    public function getAuthClientId(): string;

    /**
     * @return string
     */
    public function getAuthClientSecret(): string;

    /**
     * @return string
     */
    public function getAuthCookieSecret(): string;

    /**
     * @return string
     */
    public function getAuthAudience(): string;

    /**
     * @return string
     */
    public function getAuthOrganizationId(): string;

    /**
     * @return CacheItemPoolInterface|null
     */
    public function getCache(): ?CacheItemPoolInterface;
}
