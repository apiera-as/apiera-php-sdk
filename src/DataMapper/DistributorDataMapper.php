<?php

declare(strict_types=1);

namespace Apiera\Sdk\DataMapper;

use Apiera\Sdk\DTO\Response\Distributor\DistributorCollectionResponse;
use Apiera\Sdk\DTO\Response\Distributor\DistributorResponse;
use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Exception\ClientException;
use Apiera\Sdk\Interface\DataMapperInterface;
use Apiera\Sdk\Interface\DTO\JsonLDInterface;
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
final class DistributorDataMapper implements DataMapperInterface
{
    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     *
     * @param array<string, mixed> $responseData
     *
     * @return DistributorResponse
     */
    public function fromResponse(array $responseData): ResponseInterface
    {
        try {
            return new DistributorResponse(
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
    public function fromCollectionResponse(array $collectionResponseData): JsonLDInterface
    {
        try {
            return new DistributorCollectionResponse(
                context: $collectionResponseData['@context'],
                id: $collectionResponseData['@id'],
                type: LdType::from($collectionResponseData['@type']),
                members: array_map(
                    fn(array $distributor): DistributorResponse => $this->fromResponse($distributor),
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
     * @param \Apiera\Sdk\DTO\Request\Distributor\DistributorRequest $requestDto
     *
     * @return array<string, mixed>
     */
    public function toRequestData(RequestInterface $requestDto): array
    {
        return $requestDto->toArray();
    }
}
