<?php

declare(strict_types=1);

namespace Apiera\Sdk\DataMapper;

use Apiera\Sdk\DTO\Response\Brand\BrandCollectionResponse;
use Apiera\Sdk\DTO\Response\Brand\BrandResponse;
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
 * @author Marie Rinden <marie@shoppingnorge.no>
 * @since 0.3.0
 */
final readonly class BrandDataMapper implements DataMapperInterface
{
    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     *
     * @param array<string, mixed> $responseData
     *
     * @return BrandResponse
     */
    public function fromResponse(array $responseData): ResponseInterface
    {
        try {
            return new BrandResponse(
                id: $responseData['@id'],
                type: LdType::from($responseData['@type']),
                uuid: Uuid::fromString($responseData['uuid']),
                createdAt: new DateTimeImmutable($responseData['createdAt']),
                updatedAt: new DateTimeImmutable($responseData['updatedAt']),
                name: $responseData['name'],
                description: $responseData['description'],
                store: $responseData['store'],
                image: $responseData['image'] ?? null,
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
    public function fromCollectionResponse(array $collectionResponseData): BrandCollectionResponse
    {
        try {
            return new BrandCollectionResponse(
                context: $collectionResponseData['@context'],
                id: $collectionResponseData['@id'],
                type: LdType::from($collectionResponseData['@type']),
                members: array_map(
                    fn(array $attribute): BrandResponse => $this->fromResponse($attribute),
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
     * @param \Apiera\Sdk\DTO\Request\Brand\BrandRequest $requestDto
     *
     * @return array<string, mixed>
     */
    public function toRequestData(RequestInterface $requestDto): array
    {
        return $requestDto->toArray();
    }
}
