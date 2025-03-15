<?php

declare(strict_types=1);

namespace Apiera\Sdk\Interface;

use Psr\Cache\CacheItemPoolInterface;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 0.1.0
 */
interface ConfigurationInterface
{
    public function getBaseUrl(): string;

    public function getTimeout(): int;

    public function getUserAgent(): string;

    public function getDebugMode(): bool;

    public function getOauthDomain(): string;

    public function getOauthClientId(): string;

    public function getOauthClientSecret(): string;

    public function getOauthCookieSecret(): string;

    public function getOauthAudience(): string;

    public function getOauthOrganizationId(): string;

    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array;

    public function getCache(): CacheItemPoolInterface;

    public function getDefaultIntegration(): ?string;

    public function getDefaultInventoryLocation(): ?string;

    public function getDefaultStore(): ?string;
}
