<?php

declare(strict_types=1);

namespace Apiera\Sdk;

use Apiera\Sdk\Exception\CacheException;
use Apiera\Sdk\Exception\ConfigurationException;
use Apiera\Sdk\Factory\ApiExceptionFactory;
use Apiera\Sdk\Interface\Oauth2Interface;
use Auth0\SDK\Auth0;
use Auth0\SDK\Configuration\SdkConfiguration;
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
 * @since 0.1.0
 */
final readonly class Oauth2Handler implements Oauth2Interface
{
    private const string CACHE_PREFIX = 'oauth2_token_';
    private const int EXPIRATION_BUFFER_SECONDS = 30;

    private Auth0 $auth0;

    /**
     * @throws ConfigurationException
     */
    public function __construct(
        private Configuration $configuration,
    ) {
        $this->auth0 = new Auth0($this->createSdkConfiguration());
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws CacheException
     * @throws ConfigurationException
     */
    public function getAccessToken(): string
    {
        $cache = $this->configuration->getCache();
        $token = $this->getTokenFromCache($cache);

        if ($token !== null) {
            return $token;
        }

        try {
            $response = $this->getClientCredentials();
            $data = json_decode(
                json: $response->getBody()->getContents(),
                associative: true,
                flags: JSON_THROW_ON_ERROR
            );

            if (!isset($data['access_token'])) {
                throw ApiExceptionFactory::createFromResponse('No access token found in response');
            }

            if (isset($data['expires_in'])) {
                $this->cacheToken($cache, $data['access_token'], (int) $data['expires_in']);
            }

            return $data['access_token'];
        } catch (JsonException $exception) {
            throw ApiExceptionFactory::createFromResponse($exception->getMessage(), previous: $exception);
        }
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws CacheException
     * @throws ConfigurationException
     */
    public function getTokenExpiration(string $token): DateTimeInterface
    {
        $cache = $this->configuration->getCache();
        $expiration = $this->getExpirationFromCache($cache);

        if ($expiration !== null) {
            return $expiration;
        }

        try {
            $response = $this->getClientCredentials();
            $data = json_decode(
                json: $response->getBody()->getContents(),
                associative: true,
                flags: JSON_THROW_ON_ERROR
            );

            if (isset($data['expires_in']) && is_numeric($data['expires_in'])) {
                return (new DateTimeImmutable())
                    ->modify(sprintf('+%d seconds', (int) $data['expires_in']));
            }

            return new DateTimeImmutable();
        } catch (JsonException | DateMalformedStringException) {
            return new DateTimeImmutable();
        }
    }

    private function getCacheKey(string $suffix): string
    {
        return sprintf(
            '%s%s_%s',
            self::CACHE_PREFIX,
            $this->configuration->getOauthOrganizationId(),
            $suffix
        );
    }

    /**
     * @throws CacheException
     */
    private function getTokenFromCache(CacheItemPoolInterface $cache): ?string
    {
        try {
            $item = $cache->getItem($this->getCacheKey('token'));

            if ($item->isHit()) {
                return $item->get();
            }

            return null;
        } catch (InvalidArgumentException $exception) {
            throw new CacheException(
                message: 'Failed to retrieve token from cache: ' . $exception->getMessage(),
                previous: $exception
            );
        }
    }

    /**
     * @throws CacheException
     */
    private function getExpirationFromCache(CacheItemPoolInterface $cache): ?DateTimeInterface
    {
        try {
            $item = $cache->getItem($this->getCacheKey('expiration'));

            if ($item->isHit()) {
                return $item->get();
            }

            return null;
        } catch (InvalidArgumentException $exception) {
            throw new CacheException(
                message: 'Failed to retrieve token expiration from cache: ' . $exception->getMessage(),
                previous: $exception
            );
        }
    }

    /**
     * @throws CacheException
     */
    private function cacheToken(CacheItemPoolInterface $cache, string $token, int $expiresIn): void
    {
        try {
            $item = $cache->getItem($this->getCacheKey('token'));
            $item->set($token);
            // Subtract buffer from expiration time
            $item->expiresAfter($expiresIn - self::EXPIRATION_BUFFER_SECONDS);

            if (!$cache->save($item)) {
                throw new CacheException('Failed to save token to cache');
            }
        } catch (InvalidArgumentException $exception) {
            throw new CacheException(
                message: 'Failed to cache token: ' . $exception->getMessage(),
                previous: $exception
            );
        }
    }

    /**
     * @throws ConfigurationException
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     */
    private function getClientCredentials(): ResponseInterface
    {
        try {
            return $this->auth0->authentication()->clientCredentials([
                'audience' => $this->configuration->getOauthAudience(),
                'organization' => $this->configuration->getOauthOrganizationId(),
            ]);
        } catch (\Auth0\SDK\Exception\ConfigurationException $exception) {
            throw new ConfigurationException(
                message: 'Failed to obtain client credentials: ' . $exception->getMessage(),
                previous: $exception
            );
        } catch (NetworkException $exception) {
            throw ApiExceptionFactory::createFromResponse(
                message: $exception->getMessage(),
                previous: $exception,
            );
        }
    }

    /**
     * @throws ConfigurationException
     */
    private function createSdkConfiguration(): SdkConfiguration
    {
        $options = $this->configuration->getOptions();

        // Only use provided options if they include a handler
        $httpClient = isset($options['handler'])
            ? new \GuzzleHttp\Client($options)
            : null;

        try {
            return new SdkConfiguration(
                domain: $this->configuration->getOauthDomain(),
                clientId: $this->configuration->getOauthClientId(),
                clientSecret: $this->configuration->getOauthClientSecret(),
                httpClient: $httpClient,
                cookieSecret: $this->configuration->getOauthCookieSecret()
            );
        } catch (\Auth0\SDK\Exception\ConfigurationException $exception) {
            throw new ConfigurationException(
                message: 'Failed to initialize Auth0 client: ' . $exception->getMessage(),
                previous: $exception
            );
        }
    }
}
