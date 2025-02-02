<?php

declare(strict_types=1);

namespace Apiera\Sdk\Interface;

use Apiera\Sdk\Interface\DTO\JsonLDCollectionInterface;
use Apiera\Sdk\Interface\DTO\RequestInterface;
use Apiera\Sdk\Interface\DTO\ResponseInterface;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 0.1.0
 */
interface DataMapperInterface
{
    /**
     * Maps raw API response data to a strongly-typed DTO.
     *
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     *
     * @param array<string, mixed> $responseData
     */
    public function fromResponse(array $responseData): ResponseInterface;

    /**
     * Maps raw API collection response data to an array of strongly-typed DTOs.
     *
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     *
     * @param array<string, mixed> $collectionResponseData
     */
    public function fromCollectionResponse(array $collectionResponseData): JsonLDCollectionInterface;

    /**
     * Maps a request DTO to the format expected by the API.
     *
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     *
     * @return array<string, mixed>
     */
    public function toRequestData(RequestInterface $requestDto): array;
}
