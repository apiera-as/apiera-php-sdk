<?php

declare(strict_types=1);

namespace Apiera\Sdk\Interface;

use Apiera\Sdk\DTO\QueryParameters;
use Psr\Http\Message\ResponseInterface;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @package Apiera\Sdk\Interface
 * @since 0.1.0
 */
interface ClientInterface
{
    /**
     * @param string $endpoint
     * @param QueryParameters|null $params
     * @return ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function get(string $endpoint, ?QueryParameters $params = null): ResponseInterface;

    /**
     * @param string $endpoint
     * @param array<string, mixed> $body
     * @return ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function post(string $endpoint, array $body): ResponseInterface;

    /**
     * @param string $endpoint
     * @param array<string, mixed> $body
     * @return ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function patch(string $endpoint, array $body): ResponseInterface;

    /**
     * @param string $endpoint
     * @return ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function delete(string $endpoint): ResponseInterface;

    /**
     * @param ResponseInterface $response
     * @return array<string, mixed>
     * @throws ClientExceptionInterface
     */
    public function decodeResponse(ResponseInterface $response): array;
}
