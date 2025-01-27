<?php

namespace Apiera\Sdk\DataMapper;

use Apiera\Sdk\DTO\Request\AlternateIdentifier\AlternateIdentifierRequest;
use Apiera\Sdk\DTO\Response\AlternateIdentifier\AlternateIdentifierCollectionResponse;
use Apiera\Sdk\DTO\Response\AlternateIdentifier\AlternateIdentifierResponse;
use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Exception\ClientException;
use Apiera\Sdk\Interface\ClientExceptionInterface;
use Apiera\Sdk\Interface\DataMapperInterface;
use Apiera\Sdk\Interface\DTO\JsonLDInterface;
use Apiera\Sdk\Interface\DTO\RequestInterface;
use Apiera\Sdk\Interface\DTO\ResponseInterface;
use DateMalformedStringException;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;

/**
 * @author Marie Rinden <marie@shoppingnorge.no>
 * @package Apiera\Sdk\DataMapper
 * @since 0.2.0
 */
class AlternateIdentifierDataMapper implements DataMapperInterface
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
        } catch (DateMalformedStringException $exception) {
            throw new ClientException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    /**
     * @param array<string, mixed> $collectionData
     * @return AlternateIdentifierCollectionResponse
     * @throws ClientExceptionInterface
     */
    public function fromCollectionResponse(array $collectionData): JsonLDInterface
    {
        $members = [];
        foreach ($collectionData['member'] as $alternateIdentifier) {
            /** @var AlternateIdentifierResponse $response */
            $response = $this->fromResponse($alternateIdentifier);
            $members[] = $response;
        }

        return new AlternateIdentifierCollectionResponse(
            context: $collectionData['@context'],
            id: $collectionData['@id'],
            type: LdType::from($collectionData['@type']),
            members: $members,
            totalItems: $collectionData['totalItems'],
            view: $collectionData['view'] ?? null,
            firstPage: $collectionData['firstPage'] ?? null,
            lastPage: $collectionData['lastPage'] ?? null,
            nextPage: $collectionData['nextPage'] ?? null,
            previousPage: $collectionData['previousPage'] ?? null,
        );
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
