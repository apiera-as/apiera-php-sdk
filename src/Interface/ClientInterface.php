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
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     */
    public function get(string $endpoint, ?QueryParameters $params = null): ResponseInterface;

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     *
     * @param array<string, mixed> $body
     */
    public function post(string $endpoint, array $body): ResponseInterface;

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     *
     * @param array<string, mixed> $body
     */
    public function patch(string $endpoint, array $body): ResponseInterface;

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     */
    public function delete(string $endpoint): ResponseInterface;

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     *
     * @return array<string, mixed>
     */
    public function decodeResponse(ResponseInterface $response): array;
}
