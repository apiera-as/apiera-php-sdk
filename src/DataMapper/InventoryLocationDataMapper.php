<?php

declare(strict_types=1);

namespace Apiera\Sdk\DataMapper;

use Apiera\Sdk\DTO\Response\InventoryLocation\InventoryLocationCollectionResponse;
use Apiera\Sdk\DTO\Response\InventoryLocation\InventoryLocationResponse;
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
final readonly class InventoryLocationDataMapper implements DataMapperInterface
{
    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     *
     * @param array<string, mixed> $responseData
     *
     * @return InventoryLocationResponse
     */
    public function fromResponse(array $responseData): ResponseInterface
    {
        try {
            return new InventoryLocationResponse(
                id: $responseData['@id'],
                type: LdType::from($responseData['@type']),
                uuid: Uuid::fromString($responseData['uuid']),
                createdAt: new DateTimeImmutable($responseData['createdAt']),
                updatedAt: new DateTimeImmutable($responseData['updatedAt']),
                name: $responseData['name'],
                address1: $responseData['address1'] ?? null,
                address2: $responseData['address2'] ?? null,
                city: $responseData['city'] ?? null,
                state: $responseData['state'] ?? null,
                zipCode: $responseData['zipCode'] ?? null,
                country: $responseData['country'] ?? null,
                phone: $responseData['phone'] ?? null,
                email: $responseData['email'] ?? null,
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
    public function fromCollectionResponse(array $collectionResponseData): InventoryLocationCollectionResponse
    {
        try {
            return new InventoryLocationCollectionResponse(
                context: $collectionResponseData['@context'],
                id: $collectionResponseData['@id'],
                type: LdType::from($collectionResponseData['@type']),
                members: array_map(
                    fn(array $inventoryLocation): InventoryLocationResponse => $this->fromResponse($inventoryLocation),
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
     * @param \Apiera\Sdk\DTO\Request\Category\CategoryRequest $requestDto
     *
     * @return array<string, mixed>
     */
    public function toRequestData(RequestInterface $requestDto): array
    {
        return $requestDto->toArray();
    }
}
