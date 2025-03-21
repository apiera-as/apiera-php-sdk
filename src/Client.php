<?php

declare(strict_types=1);

namespace Apiera\Sdk;

use Apiera\Sdk\DTO\QueryParameters;
use Apiera\Sdk\Enum\ContentTypes;
use Apiera\Sdk\Enum\HttpHeaders;
use Apiera\Sdk\Factory\ApiExceptionFactory;
use Apiera\Sdk\Interface\ClientInterface;
use Apiera\Sdk\Interface\Oauth2Interface;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use GuzzleHttp\RequestOptions;
use JsonException;
use Psr\Http\Message\ResponseInterface;
use Throwable;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 0.1.0
 */
final readonly class Client implements ClientInterface
{
    private GuzzleClientInterface $client;
    private Oauth2Interface $oauth2Handler;

    /**
     * @throws \Apiera\Sdk\Exception\ConfigurationException
     */
    public function __construct(
        Configuration $configuration,
    ) {
        $this->client = new GuzzleClient(array_merge([
            HttpHeaders::BaseUrl->value => $configuration->getBaseUrl(),
            RequestOptions::TIMEOUT => $configuration->getTimeout(),
            RequestOptions::DEBUG => $configuration->getDebugMode(),
            RequestOptions::HEADERS => [
                HttpHeaders::UserAgent->value => $configuration->getUserAgent(),
                HttpHeaders::ContentType->value => ContentTypes::JsonLD->value,
            ],
        ], $configuration->getOptions()));

        $this->oauth2Handler = new Oauth2Handler($configuration);
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     */
    public function get(string $endpoint, ?QueryParameters $params = null): ResponseInterface
    {
        try {
            $options = $params !== null ? [RequestOptions::QUERY => $params->toArray()] : [];

            return $this->client->request('GET', $endpoint, $this->mergeOptions($options));
        } catch (Throwable $exception) {
            throw ApiExceptionFactory::createFromResponse(
                message: $exception->getMessage(),
                response: method_exists($exception, 'getResponse') ? $exception->getResponse() : null,
                request: method_exists($exception, 'getRequest') ? $exception->getRequest() : null,
                previous: $exception,
            );
        }
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     *
     * @param array<string, mixed> $body
     */
    public function post(string $endpoint, array $body): ResponseInterface
    {
        try {
            return $this->client->request('POST', $endpoint, $this->mergeOptions([
                RequestOptions::JSON => $body,
                RequestOptions::HEADERS => [
                    HttpHeaders::ContentType->value => ContentTypes::JsonLD->value,
                ],
            ]));
        } catch (Throwable $exception) {
            throw ApiExceptionFactory::createFromResponse(
                message: $exception->getMessage(),
                response: method_exists($exception, 'getResponse') ? $exception->getResponse() : null,
                request: method_exists($exception, 'getRequest') ? $exception->getRequest() : null,
                previous: $exception,
            );
        }
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     *
     * @param array<string, mixed> $body
     */
    public function patch(string $endpoint, array $body): ResponseInterface
    {
        try {
            return $this->client->request('PATCH', $endpoint, $this->mergeOptions([
                RequestOptions::JSON => $body,
                RequestOptions::HEADERS => [
                    HttpHeaders::ContentType->value => ContentTypes::MergePatch->value,
                ],
            ]));
        } catch (Throwable $exception) {
            throw ApiExceptionFactory::createFromResponse(
                message: $exception->getMessage(),
                response: method_exists($exception, 'getResponse') ? $exception->getResponse() : null,
                request: method_exists($exception, 'getRequest') ? $exception->getRequest() : null,
                previous: $exception,
            );
        }
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     */
    public function delete(string $endpoint): ResponseInterface
    {
        try {
            return $this->client->request('DELETE', $endpoint, $this->mergeOptions([]));
        } catch (Throwable $exception) {
            throw ApiExceptionFactory::createFromResponse(
                message: $exception->getMessage(),
                response: method_exists($exception, 'getResponse') ? $exception->getResponse() : null,
                request: method_exists($exception, 'getRequest') ? $exception->getRequest() : null,
                previous: $exception,
            );
        }
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     *
     * @return array<string, mixed>
     */
    public function decodeResponse(ResponseInterface $response): array
    {
        try {
            return json_decode(
                json: $response->getBody()->getContents(),
                associative: true,
                flags: JSON_THROW_ON_ERROR | JSON_PRESERVE_ZERO_FRACTION
            );
        } catch (JsonException $exception) {
            throw ApiExceptionFactory::createFromResponse($exception->getMessage(), previous: $exception);
        }
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\CacheException
     * @throws \Apiera\Sdk\Exception\ConfigurationException
     *
     * @return array<string, string>
     */
    private function getAuthHeaders(): array
    {
        $token = $this->oauth2Handler->getAccessToken();

        return [HttpHeaders::Authorization->value => 'Bearer ' . $token];
    }

    /**
     * Merge default options with request-specific options
     *
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\CacheException
     * @throws \Apiera\Sdk\Exception\ConfigurationException
     *
     * @param array<string, mixed> $options
     *
     * @return array<string, mixed>
     */
    private function mergeOptions(array $options): array
    {
        $headers = array_merge(
            $options[RequestOptions::HEADERS] ?? [],
            $this->getAuthHeaders()
        );

        return array_merge(
            $options,
            [RequestOptions::HEADERS => $headers]
        );
    }
}
