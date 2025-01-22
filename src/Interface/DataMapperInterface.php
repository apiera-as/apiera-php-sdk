<?php

declare(strict_types=1);

namespace Apiera\Sdk\Interface;

use Apiera\Sdk\Interface\DTO\JsonLDInterface;
use Apiera\Sdk\Interface\DTO\RequestInterface;
use Apiera\Sdk\Interface\DTO\ResponseInterface;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @package Apiera\Sdk\Contract
 * @since 1.0.0
 */
interface DataMapperInterface
{
    /**
     * Maps raw API response data to a strongly-typed DTO.
     *
     * @param array<string, mixed> $responseData
     * @return ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function fromResponse(array $responseData): ResponseInterface;

    /**
     * Maps raw API collection response data to an array of strongly-typed DTOs.
     *
     * @param array<string, mixed> $collectionData
     * @return JsonLDInterface
     * @throws ClientExceptionInterface
     */
    public function fromCollectionResponse(array $collectionData): JsonLDInterface;

    /**
     * Maps a request DTO to the format expected by the API.
     *
     * @param RequestInterface $requestDto
     * @return array<string, mixed>
     */
    public function toRequestData(RequestInterface $requestDto): array;
}
