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
final readonly class Configuration implements ConfigurationInterface
{
    /**
     * @param int $timeout The request timeout in seconds. Defaults to 10.
     * @param bool $debugMode Enables or disables debug mode. Defaults to false.
     * @param string $baseUrl The base URL of the API.
     * @param string $userAgent The user agent string for HTTP requests.
     * @param string $oauthDomain OAuth2 authentication domain.
     * @param string $oauthClientId OAuth2 client ID.
     * @param string $oauthClientSecret OAuth2 client secret.
     * @param string $oauthCookieSecret OAuth2 cookie secret.
     * @param string $oauthAudience Oauth2 audience
     * @param string $oauthOrganizationId OAuth2 organization ID.
     * @param array<string, mixed> $options
     * @param CacheItemPoolInterface $cache
     */
    public function __construct(
        private string $baseUrl,
        private string $userAgent,
        private string $oauthDomain,
        private string $oauthClientId,
        private string $oauthClientSecret,
        private string $oauthCookieSecret,
        private string $oauthAudience,
        private string $oauthOrganizationId,
        private CacheItemPoolInterface $cache,
        private int $timeout = 10,
        private bool $debugMode = false,
        private array $options = [],
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
    public function getOauthDomain(): string
    {
        return $this->oauthDomain;
    }

    /**
     * @return string
     */
    public function getOauthClientId(): string
    {
        return $this->oauthClientId;
    }

    /**
     * @return string
     */
    public function getOauthClientSecret(): string
    {
        return $this->oauthClientSecret;
    }

    /**
     * @return string
     */
    public function getOauthCookieSecret(): string
    {
        return $this->oauthCookieSecret;
    }

    public function getOauthAudience(): string
    {
        return $this->oauthAudience;
    }

    /**
     * @return string
     */
    public function getOauthOrganizationId(): string
    {
        return $this->oauthOrganizationId;
    }

    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @return CacheItemPoolInterface
     */
    public function getCache(): CacheItemPoolInterface
    {
        return $this->cache;
    }
}
