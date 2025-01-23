<?php

namespace Apiera\Sdk\DataMapper;

use Apiera\Sdk\DTO\Request\Attribute\AttributeCollectionResponse;
use Apiera\Sdk\DTO\Request\Attribute\AttributeRequest;
use Apiera\Sdk\DTO\Response\Attribute\AttributeResponse;
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
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @package Apiera\Sdk\DataMapper
 * @since 0.2.0
 */
class AttributeDataMapper implements DataMapperInterface
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
        } catch (DateMalformedStringException $exception) {
            throw new ClientException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    /**
     * @param array<string, mixed> $collectionData
     * @return AttributeCollectionResponse
     * @throws ClientExceptionInterface
     */
    public function fromCollectionResponse(array $collectionData): JsonLDInterface
    {
        $members = [];
        foreach ($collectionData['member'] as $attribute) {
            /** @var AttributeResponse $response */
            $response = $this->fromResponse($attribute);
            $members[] = $response;
        }

        return new AttributeCollectionResponse(
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
     * @param AttributeRequest $requestDto
     * @return array<string, mixed>
     */
    public function toRequestData(RequestInterface $requestDto): array
    {
        return $requestDto->toArray();
    }
}
