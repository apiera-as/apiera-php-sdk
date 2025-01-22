<?php

declare(strict_types=1);

namespace Apiera\Sdk\DataMapper;

use Apiera\Sdk\DTO\Request\Category\CategoryRequest;
use Apiera\Sdk\DTO\Response\Category\CategoryCollectionResponse;
use Apiera\Sdk\DTO\Response\Category\CategoryResponse;
use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Exception\ClientException;
use Apiera\Sdk\Interface\ClientExceptionInterface;
use Apiera\Sdk\Interface\DataMapperInterface;
use Apiera\Sdk\Interface\DTO\RequestInterface;
use Apiera\Sdk\Interface\DTO\ResponseInterface;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @package Apiera\Sdk\DataMapper
 * @since 1.0.0
 */
class CategoryDataMapper implements DataMapperInterface
{
    /**
     * @param array<string, mixed> $responseData
     * @return CategoryResponse
     * @throws ClientExceptionInterface
     */
    public function fromResponse(array $responseData): ResponseInterface
    {
        try {
            return new CategoryResponse(
                id: $responseData['@id'],
                type: LdType::from($responseData['@type']),
                uuid: Uuid::fromString($responseData['uuid']),
                createdAt: new DateTimeImmutable($responseData['createdAt']),
                updatedAt: new DateTimeImmutable($responseData['updatedAt']),
                name: $responseData['name'],
                store: $responseData['store'],
                description: $responseData['description'],
                parent: $responseData['parent'],
                image: $responseData['image'],
            );
        } catch (\DateMalformedStringException $exception) {
            throw new ClientException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    /**
     * @param array<string, mixed> $collectionData
     * @return CategoryCollectionResponse
     * @throws ClientExceptionInterface
     */
    public function fromCollectionResponse(array $collectionData): CategoryCollectionResponse
    {
        $members = [];
        foreach ($collectionData['member'] as $category) {
            /** @var CategoryResponse $response */
            $response = $this->fromResponse($category);
            $members[] = $response;
        }

        return new CategoryCollectionResponse(
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
     * @param CategoryRequest $requestDto
     * @return array<string, mixed>
     */
    public function toRequestData(RequestInterface $requestDto): array
    {
        return $requestDto->toArray();
    }
}
