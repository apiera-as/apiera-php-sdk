<?php

declare(strict_types=1);

namespace Apiera\Sdk\DataMapper;

use Apiera\Sdk\DTO\Response\Property\PropertyCollectionResponse;
use Apiera\Sdk\DTO\Response\Property\PropertyResponse;
use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Exception\ClientException;
use Apiera\Sdk\Interface\DataMapperInterface;
use Apiera\Sdk\Interface\DTO\RequestInterface;
use Apiera\Sdk\Interface\DTO\ResponseInterface;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;
use Throwable;
use ValueError;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 0.3.0
 */
final class PropertyDataMapper implements DataMapperInterface
{
    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     *
     * @param array<string, mixed> $responseData
     *
     * @return PropertyResponse
     */
    public function fromResponse(array $responseData): ResponseInterface
    {
        try {
            return new PropertyResponse(
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
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     *
     * @param array<string, mixed> $collectionResponseData
     */
    public function fromCollectionResponse(array $collectionResponseData): PropertyCollectionResponse
    {
        try {
            return new PropertyCollectionResponse(
                context: $collectionResponseData['@context'],
                id: $collectionResponseData['@id'],
                type: LdType::from($collectionResponseData['@type']),
                members: array_map(
                    fn(array $property): PropertyResponse => $this->fromResponse($property),
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
     * @param \Apiera\Sdk\DTO\Request\Property\PropertyRequest $requestDto
     *
     * @return array<string, mixed>
     */
    public function toRequestData(RequestInterface $requestDto): array
    {
        return $requestDto->toArray();
    }
}
