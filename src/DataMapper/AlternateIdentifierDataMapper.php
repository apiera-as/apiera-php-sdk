<?php

declare(strict_types=1);

namespace Apiera\Sdk\DataMapper;

use Apiera\Sdk\DTO\Request\AlternateIdentifier\AlternateIdentifierRequest;
use Apiera\Sdk\DTO\Response\AlternateIdentifier\AlternateIdentifierCollectionResponse;
use Apiera\Sdk\DTO\Response\AlternateIdentifier\AlternateIdentifierResponse;
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
 * @author Marie Rinden <marie@shoppingnorge.no>
 * @package Apiera\Sdk\DataMapper
 * @since 0.2.0
 */
final readonly class AlternateIdentifierDataMapper implements DataMapperInterface
{
    /**
     * @param array<string, mixed> $responseData
     * @return AlternateIdentifierResponse
     * @throws ClientExceptionInterface
     */
    public function fromResponse(array $responseData): ResponseInterface
    {
        try {
            return new AlternateIdentifierResponse(
                id: $responseData['@id'],
                type: LdType::from($responseData['@type']),
                uuid: Uuid::fromString($responseData['uuid']),
                createdAt: new DateTimeImmutable($responseData['createdAt']),
                updatedAt: new DateTimeImmutable($responseData['updatedAt']),
                identifierType: $responseData['type'],
                code: $responseData['code'],
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
     * @return AlternateIdentifierCollectionResponse
     * @throws ClientExceptionInterface
     */
    public function fromCollectionResponse(array $collectionResponseData): AlternateIdentifierCollectionResponse
    {
        try {
            $members = array_map(
                fn(array $alternateIdentifier): AlternateIdentifierResponse =>
                $this->fromResponse($alternateIdentifier),
                $collectionResponseData['member']
            );

            return new AlternateIdentifierCollectionResponse(
                context: $collectionResponseData['@context'],
                id: $collectionResponseData['@id'],
                type: LdType::from($collectionResponseData['@type']),
                members: $members,
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
     * @param AlternateIdentifierRequest $requestDto
     * @return array<string, mixed>
     */
    public function toRequestData(RequestInterface $requestDto): array
    {
        return $requestDto->toArray();
    }
}
