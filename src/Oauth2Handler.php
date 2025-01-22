<?php

declare(strict_types=1);

namespace Apiera\Sdk;

use Apiera\Sdk\Exception\ClientException;
use Auth0\SDK\Auth0;
use Auth0\SDK\Configuration\SdkConfiguration;
use Auth0\SDK\Exception\ConfigurationException;
use Auth0\SDK\Exception\NetworkException;
use DateMalformedStringException;
use DateTimeImmutable;
use DateTimeInterface;
use JsonException;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @package Apiera\Sdk
 * @since 0.1.0
 */
readonly final class Oauth2Handler implements Interface\Oauth2Interface
{
    private const string CACHE_PREFIX = 'oauth2_token_';
    private const int EXPIRATION_BUFFER_SECONDS = 30;

    private Auth0 $auth0;

    /**
     * @param Configuration $configuration
     * @throws ClientException
     */
    public function __construct(
        private Configuration $configuration,
    ) {
        try {
            $this->auth0 = new Auth0($this->createSdkConfiguration());
        } catch (ConfigurationException $e) {
            throw new ClientException(
                message: 'Failed to initialize Auth0 client: ' . $e->getMessage(),
                previous: $e
            );
        }
    }

    /**
     * @return string
     * @throws ClientException
     */
    public function getAccessToken(): string
    {
        $cache = $this->configuration->getCache();
        if ($cache !== null) {
            $token = $this->getTokenFromCache($cache);
            if ($token !== null) {
                return $token;
            }
        }

        try {
            $response = $this->getClientCredentials();
            $data = json_decode(
                json: $response->getBody()->getContents(),
                associative: true,
                flags: JSON_THROW_ON_ERROR
            );

            if (!isset($data['access_token'])) {
                throw new ClientException('No access token found in response');
            }

            if ($cache !== null && isset($data['expires_in'])) {
                $this->cacheToken($cache, $data['access_token'], (int) $data['expires_in']);
            }

            return $data['access_token'];
        } catch (JsonException $e) {
            throw new ClientException(
                message: 'Failed to decode auth response: ' . $e->getMessage(),
                previous: $e
            );
        }
    }

    /**
     * @param string $token
     * @return DateTimeInterface
     * @throws ClientException
     */
    public function getTokenExpiration(string $token): DateTimeInterface
    {
        $cache = $this->configuration->getCache();
        if ($cache !== null) {
            $expiration = $this->getExpirationFromCache($cache);
            if ($expiration !== null) {
                return $expiration;
            }
        }

        try {
            $response = $this->getClientCredentials();
            $data = json_decode(
                json: $response->getBody()->getContents(),
                associative: true,
                flags: JSON_THROW_ON_ERROR
            );

            if (isset($data['expires_in']) && is_numeric($data['expires_in'])) {
                $expiration = (new DateTimeImmutable())
                    ->modify(sprintf('+%d seconds', (int) $data['expires_in']));

                if ($cache !== null) {
                    $this->cacheExpiration($cache, $expiration);
                }

                return $expiration;
            }

            return new DateTimeImmutable();
        } catch (JsonException | DateMalformedStringException) {
            return new DateTimeImmutable();
        }
    }

    /**
     * @param string $suffix
     * @return string
     */
    private function getCacheKey(string $suffix): string
    {
        return sprintf(
            '%s%s_%s',
            self::CACHE_PREFIX,
            $this->configuration->getAuthOrganizationId(),
            $suffix
        );
    }

    /**
     * @param CacheItemPoolInterface $cache
     * @return string|null
     * @throws ClientException
     */
    private function getTokenFromCache(CacheItemPoolInterface $cache): ?string
    {
        try {
            $item = $cache->getItem($this->getCacheKey('token'));
            if ($item->isHit()) {
                return $item->get();
            }
            return null;
        } catch (InvalidArgumentException $e) {
            throw new ClientException(
                message: 'Failed to retrieve token from cache: ' . $e->getMessage(),
                previous: $e
            );
        }
    }

    /**
     * @param CacheItemPoolInterface $cache
     * @return DateTimeInterface|null
     * @throws ClientException
     */
    private function getExpirationFromCache(CacheItemPoolInterface $cache): ?DateTimeInterface
    {
        try {
            $item = $cache->getItem($this->getCacheKey('expiration'));
            if ($item->isHit()) {
                return $item->get();
            }
            return null;
        } catch (InvalidArgumentException $e) {
            throw new ClientException(
                message: 'Failed to retrieve token expiration from cache: ' . $e->getMessage(),
                previous: $e
            );
        }
    }

    /**
     * @param CacheItemPoolInterface $cache
     * @param string $token
     * @param int $expiresIn
     * @return void
     * @throws ClientException
     */
    private function cacheToken(CacheItemPoolInterface $cache, string $token, int $expiresIn): void
    {
        try {
            $item = $cache->getItem($this->getCacheKey('token'));
            $item->set($token);
            // Subtract buffer from expiration time
            $item->expiresAfter($expiresIn - self::EXPIRATION_BUFFER_SECONDS);
            if (!$cache->save($item)) {
                throw new ClientException('Failed to save token to cache');
            }
        } catch (InvalidArgumentException $e) {
            throw new ClientException(
                message: 'Failed to cache token: ' . $e->getMessage(),
                previous: $e
            );
        }
    }

    /**
     * @param CacheItemPoolInterface $cache
     * @param DateTimeInterface $expiration
     * @return void
     * @throws ClientException
     */
    private function cacheExpiration(CacheItemPoolInterface $cache, DateTimeInterface $expiration): void
    {
        try {
            $item = $cache->getItem($this->getCacheKey('expiration'));
            $item->set($expiration);
            $item->expiresAt($expiration);
            if (!$cache->save($item)) {
                throw new ClientException('Failed to save token expiration to cache');
            }
        } catch (InvalidArgumentException $e) {
            throw new ClientException(
                message: 'Failed to cache token expiration: ' . $e->getMessage(),
                previous: $e
            );
        }
    }

    /**
     * @return ResponseInterface
     * @throws ClientException
     */
    private function getClientCredentials(): ResponseInterface
    {
        try {
            return $this->auth0->authentication()->clientCredentials([
                'audience' => $this->configuration->getAuthAudience(),
                'organization' => $this->configuration->getAuthOrganizationId(),
            ]);
        } catch (NetworkException | ConfigurationException $e) {
            throw new ClientException(
                message: 'Failed to obtain client credentials: ' . $e->getMessage(),
                previous: $e
            );
        }
    }

    /**
     * @return SdkConfiguration
     * @throws ConfigurationException
     */
    private function createSdkConfiguration(): SdkConfiguration
    {
        return new SdkConfiguration(
            domain: $this->configuration->getAuthDomain(),
            clientId: $this->configuration->getAuthClientId(),
            clientSecret: $this->configuration->getAuthClientSecret(),
            cookieSecret: $this->configuration->getAuthCookieSecret(),
        );
    }
}
