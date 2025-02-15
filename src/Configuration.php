<?php

declare(strict_types=1);

namespace Apiera\Sdk;

use Apiera\Sdk\Interface\ConfigurationInterface;
use Psr\Cache\CacheItemPoolInterface;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
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
     * @param string|null $defaultIntegration IRI reference
     * @param string|null $defaultInventoryLocation IRI reference
     * @param string|null $defaultStore IRI reference
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
        private ?string $defaultIntegration = null,
        private ?string $defaultInventoryLocation = null,
        private ?string $defaultStore = null,
    ) {
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    public function getTimeout(): int
    {
        return $this->timeout;
    }

    public function getUserAgent(): string
    {
        return $this->userAgent;
    }

    public function getDebugMode(): bool
    {
        return $this->debugMode;
    }

    public function getOauthDomain(): string
    {
        return $this->oauthDomain;
    }

    public function getOauthClientId(): string
    {
        return $this->oauthClientId;
    }

    public function getOauthClientSecret(): string
    {
        return $this->oauthClientSecret;
    }

    public function getOauthCookieSecret(): string
    {
        return $this->oauthCookieSecret;
    }

    public function getOauthAudience(): string
    {
        return $this->oauthAudience;
    }

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

    public function getCache(): CacheItemPoolInterface
    {
        return $this->cache;
    }

    public function getDefaultIntegration(): ?string
    {
        return $this->defaultIntegration;
    }

    public function getDefaultInventoryLocation(): ?string
    {
        return $this->defaultInventoryLocation;
    }

    public function getDefaultStore(): ?string
    {
        return $this->defaultStore;
    }
}
