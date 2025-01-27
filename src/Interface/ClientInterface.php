<?php

declare(strict_types=1);

namespace Apiera\Sdk\Interface;

use Apiera\Sdk\DTO\QueryParameters;
use Psr\Http\Message\ResponseInterface;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 0.1.0
 */
interface ClientInterface
{
    /**
     * @throws ClientExceptionInterface
     */
    public function get(string $endpoint, ?QueryParameters $params = null): ResponseInterface;

    /**
     * @throws ClientExceptionInterface
     *
     * @param array<string, mixed> $body
     */
    public function post(string $endpoint, array $body): ResponseInterface;

    /**
     * @throws ClientExceptionInterface
     *
     * @param array<string, mixed> $body
     */
    public function patch(string $endpoint, array $body): ResponseInterface;

    /**
     * @throws ClientExceptionInterface
     */
    public function delete(string $endpoint): ResponseInterface;

    /**
     * @throws ClientExceptionInterface
     *
     * @return array<string, mixed>
     */
    public function decodeResponse(ResponseInterface $response): array;
}
