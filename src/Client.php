<?php

declare(strict_types=1);

namespace Apiera\Sdk;

use Apiera\Sdk\DTO\QueryParameters;
use Apiera\Sdk\Enum\ContentTypes;
use Apiera\Sdk\Enum\HttpHeaders;
use Apiera\Sdk\Exception\ClientException;
use Apiera\Sdk\Interface\ClientExceptionInterface;
use Apiera\Sdk\Interface\ClientInterface;
use Apiera\Sdk\Interface\Oauth2Interface;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use JsonException;
use Psr\Http\Message\ResponseInterface;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @package Apiera\Sdk
 * @since 0.1.0
 */
readonly class Client implements ClientInterface
{
    private GuzzleClientInterface $client;
    private Oauth2Interface $oauth2Handler;

    /**
     * @param Configuration $configuration
     * @throws ClientException
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
            ]
        ], $configuration->getOptions()));

        $this->oauth2Handler = new Oauth2Handler($configuration);
    }

    /**
     * Get authorization headers with OAuth2 token
     *
     * @return array<string, string>
     * @throws ClientExceptionInterface
     */
    private function getAuthHeaders(): array
    {
        $token = $this->oauth2Handler->getAccessToken();
        return [HttpHeaders::Authorization->value => 'Bearer ' . $token];
    }

    /**
     * Merge default options with request-specific options
     *
     * @param array<string, mixed> $options
     * @return array<string, mixed>
     * @throws ClientExceptionInterface
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

    /**
     * @param string $endpoint
     * @param QueryParameters|null $params
     * @return ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function get(string $endpoint, ?QueryParameters $params = null): ResponseInterface
    {
        try {
            $options = $params !== null ? [RequestOptions::QUERY => $params->toArray()] : [];
            return $this->client->request('GET', $endpoint, $this->mergeOptions($options));
        } catch (GuzzleException $exception) {
            throw new ClientException(
                message: $exception->getMessage(),
                code: $exception->getCode(),
                previous: $exception,
                request: method_exists($exception, 'getRequest') ? $exception->getRequest() : null,
                response: method_exists($exception, 'getResponse') ? $exception->getResponse() : null
            );
        }
    }

    /**
     * @param string $endpoint
     * @param array<string, mixed> $body
     * @return ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function post(string $endpoint, array $body): ResponseInterface
    {
        try {
            return $this->client->request('POST', $endpoint, $this->mergeOptions([
                RequestOptions::JSON => $body,
                RequestOptions::HEADERS => [
                    HttpHeaders::ContentType->value => ContentTypes::JsonLD->value
                ]
            ]));
        } catch (GuzzleException $exception) {
            throw new ClientException(
                message: $exception->getMessage(),
                code: $exception->getCode(),
                previous: $exception,
                request: method_exists($exception, 'getRequest') ? $exception->getRequest() : null,
                response: method_exists($exception, 'getResponse') ? $exception->getResponse() : null
            );
        }
    }

    /**
     * @param string $endpoint
     * @param array<string, mixed> $body
     * @return ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function patch(string $endpoint, array $body): ResponseInterface
    {
        try {
            return $this->client->request('PATCH', $endpoint, $this->mergeOptions([
                RequestOptions::JSON => $body,
                RequestOptions::HEADERS => [
                    HttpHeaders::ContentType->value => ContentTypes::MergePatch->value,
                ]
            ]));
        } catch (GuzzleException $exception) {
            throw new ClientException(
                message: $exception->getMessage(),
                code: $exception->getCode(),
                previous: $exception,
                request: method_exists($exception, 'getRequest') ? $exception->getRequest() : null,
                response: method_exists($exception, 'getResponse') ? $exception->getResponse() : null
            );
        }
    }

    /**
     * @param string $endpoint
     * @return ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function delete(string $endpoint): ResponseInterface
    {
        try {
            return $this->client->request('DELETE', $endpoint, $this->mergeOptions([]));
        } catch (GuzzleException $exception) {
            throw new ClientException(
                message: $exception->getMessage(),
                code: $exception->getCode(),
                previous: $exception,
                request: method_exists($exception, 'getRequest') ? $exception->getRequest() : null,
                response: method_exists($exception, 'getResponse') ? $exception->getResponse() : null
            );
        }
    }

    /**
     * @param ResponseInterface $response
     * @return array<string, mixed>
     * @throws ClientExceptionInterface
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
            throw new ClientException(
                message: $exception->getMessage(),
                code: $exception->getCode(),
                previous: $exception,
            );
        }
    }
}
