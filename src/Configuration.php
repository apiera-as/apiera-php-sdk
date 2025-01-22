<?php

declare(strict_types=1);

namespace Apiera\Sdk;

use Apiera\Sdk\Interface\ConfigurationInterface;
use Psr\Cache\CacheItemPoolInterface;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @package Apiera\Sdk
 * @since 0.1.0
 */
readonly class Configuration implements ConfigurationInterface
{
    /**
     * @param int $timeout The request timeout in seconds. Defaults to 10.
     * @param bool $debugMode Enables or disables debug mode. Defaults to false.
     * @param string $baseUrl The base URL of the API.
     * @param string $userAgent The user agent string for HTTP requests.
     * @param string $authDomain OAuth2 authentication domain.
     * @param string $authClientId OAuth2 client ID.
     * @param string $authClientSecret OAuth2 client secret.
     * @param string $authCookieSecret OAuth2 cookie secret.
     * @param string $authAudience Oauth2 audience
     * @param string $authOrganizationId OAuth2 organization ID.
     * @param CacheItemPoolInterface|null $cache
     */
    public function __construct(
        private int $timeout = 10,
        private bool $debugMode = false,
        private string $baseUrl = '',
        private string $userAgent = '',
        private string $authDomain = '',
        private string $authClientId = '',
        private string $authClientSecret = '',
        private string $authCookieSecret = '',
        private string $authAudience = '',
        private string $authOrganizationId = '',
        private ?CacheItemPoolInterface $cache = null,
    ) {
    }

    /**
     * @return string
     */
    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * @return int
     */
    public function getTimeout(): int
    {
        return $this->timeout;
    }

    /**
     * @return string
     */
    public function getUserAgent(): string
    {
        return $this->userAgent;
    }

    /**
     * @return bool
     */
    public function getDebugMode(): bool
    {
        return $this->debugMode;
    }

    /**
     * @return string
     */
    public function getAuthDomain(): string
    {
        return $this->authDomain;
    }

    /**
     * @return string
     */
    public function getAuthClientId(): string
    {
        return $this->authClientId;
    }

    /**
     * @return string
     */
    public function getAuthClientSecret(): string
    {
        return $this->authClientSecret;
    }

    /**
     * @return string
     */
    public function getAuthCookieSecret(): string
    {
        return $this->authCookieSecret;
    }

    public function getAuthAudience(): string
    {
        return $this->authAudience;
    }

    /**
     * @return string
     */
    public function getAuthOrganizationId(): string
    {
        return $this->authOrganizationId;
    }

    public function getCache(): ?CacheItemPoolInterface
    {
        return $this->cache;
    }
}
