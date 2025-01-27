<?php

declare(strict_types=1);

namespace Apiera\Sdk\DataMapper;

use Apiera\Sdk\DTO\Request\Attribute\AttributeRequest;
use Apiera\Sdk\DTO\Response\Attribute\AttributeCollectionResponse;
use Apiera\Sdk\DTO\Response\Attribute\AttributeResponse;
use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Exception\ClientException;
use Apiera\Sdk\Interface\ClientExceptionInterface;
use Apiera\Sdk\Interface\DataMapperInterface;
use Apiera\Sdk\Interface\DTO\RequestInterface;
use Apiera\Sdk\Interface\DTO\ResponseInterface;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;
use Throwable;
use ValueError;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @package Apiera\Sdk\DataMapper
 * @since 0.2.0
 */
final class AttributeDataMapper implements DataMapperInterface
{
    /**
     * @param array<string, mixed> $responseData
     * @return AttributeResponse
     * @throws ClientExceptionInterface
     */
    public function fromResponse(array $responseData): ResponseInterface
    {
        try {
            return new AttributeResponse(
                id: $responseData['@id'],
                type: LdType::from($responseData['@type']),
                uuid: Uuid::fromString($responseData['uuid']),
                createdAt: new DateTimeImmutable($responseData['createdAt']),
                updatedAt: new DateTimeImmutable($responseData['updatedAt']),
                name: $responseData['name'],
                store: $responseData['store'],
            );
        } catch (Throwable $exception) {
            throw new ClientException(
                message: 'Failed to map response data: ' . $exception->getMessage(),
                previous: $exception
            );
        }
    }

    /**
     * @param array<string, mixed> $collectionResponseData
     * @return AttributeCollectionResponse
     * @throws ClientExceptionInterface
     */
    public function fromCollectionResponse(array $collectionResponseData): AttributeCollectionResponse
    {
        try {
            return new AttributeCollectionResponse(
                context: $collectionResponseData['@context'],
                id: $collectionResponseData['@id'],
                type: LdType::from($collectionResponseData['@type']),
                members: array_map(
                    fn(array $attribute): AttributeResponse => $this->fromResponse($attribute),
                    $collectionResponseData['member']
                ),
                totalItems: $collectionResponseData['totalItems'],
                view: $collectionResponseData['view'] ?? null,
                firstPage: $collectionResponseData['firstPage'] ?? null,
                lastPage: $collectionResponseData['lastPage'] ?? null,
                nextPage: $collectionResponseData['nextPage'] ?? null,
                previousPage: $collectionResponseData['previousPage'] ?? null,
            );
        } catch (ValueError $exception) {
            throw new ClientException(
                message: 'Invalid collection type: ' . $exception->getMessage(),
                previous: $exception
            );
        }
    }

    /**
     * @param AttributeRequest $requestDto
     * @return array<string, mixed>
     */
    public function toRequestData(RequestInterface $requestDto): array
    {
        return $requestDto->toArray();
    }
}
