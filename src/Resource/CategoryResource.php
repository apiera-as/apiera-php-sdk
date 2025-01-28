<?php

declare(strict_types=1);

namespace Apiera\Sdk\Resource;

use Apiera\Sdk\DTO\QueryParameters;
use Apiera\Sdk\DTO\Request\Category\CategoryRequest;
use Apiera\Sdk\DTO\Response\Category\CategoryCollectionResponse;
use Apiera\Sdk\DTO\Response\Category\CategoryResponse;
use Apiera\Sdk\Exception\InvalidRequestException;
use Apiera\Sdk\Interface\ClientInterface;
use Apiera\Sdk\Interface\DataMapperInterface;
use Apiera\Sdk\Interface\DTO\RequestInterface;
use Apiera\Sdk\Interface\RequestResourceInterface;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 0.1.0
 */
final readonly class CategoryResource implements RequestResourceInterface
{
    private const string ENDPOINT = '/categories';

    public function __construct(
        private ClientInterface $client,
        private DataMapperInterface $mapper,
    ) {
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws InvalidRequestException
     */
    public function find(RequestInterface $request, ?QueryParameters $params = null): CategoryCollectionResponse
    {
        if (!$request instanceof CategoryRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', CategoryRequest::class)
            );
        }

        if (!$request->getStore()) {
            throw new InvalidRequestException('Store IRI is required for this operation');
        }

        /** @var CategoryCollectionResponse $collectionResponse */
        $collectionResponse = $this->mapper->fromCollectionResponse($this->client->decodeResponse(
            $this->client->get($request->getStore() . self::ENDPOINT, $params)
        ));

        return $collectionResponse;
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws InvalidRequestException
     */
    public function findOneBy(RequestInterface $request, QueryParameters $params): CategoryResponse
    {
        if (!$request instanceof CategoryRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', CategoryRequest::class)
            );
        }

        $collection = $this->find($request, $params);

        if ($collection->getTotalItems() < 1) {
            throw new InvalidRequestException('No category found matching the given criteria');
        }

        return $collection->getMembers()[0];
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws InvalidRequestException
     */
    public function get(RequestInterface $request): CategoryResponse
    {
        if (!$request instanceof CategoryRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', CategoryRequest::class)
            );
        }

        if (!$request->getIri()) {
            throw new InvalidRequestException('Category IRI is required for this operation');
        }

        /** @var CategoryResponse $response */
        $response = $this->mapper->fromResponse($this->client->decodeResponse(
            $this->client->get($request->getIri())
        ));

        return $response;
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws InvalidRequestException
     */
    public function create(RequestInterface $request): CategoryResponse
    {
        if (!$request instanceof CategoryRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', CategoryRequest::class)
            );
        }

        if (!$request->getStore()) {
            throw new InvalidRequestException('Store IRI is required for this operation');
        }

        $requestData = $this->mapper->toRequestData($request);

        /** @var CategoryResponse $response */
        $response = $this->mapper->fromResponse($this->client->decodeResponse(
            $this->client->post($request->getStore() . self::ENDPOINT, $requestData)
        ));

        return $response;
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws InvalidRequestException
     */
    public function update(RequestInterface $request): CategoryResponse
    {
        if (!$request instanceof CategoryRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', CategoryRequest::class)
            );
        }

        if (!$request->getIri()) {
            throw new InvalidRequestException('Category IRI is required for this operation');
        }

        $requestData = $this->mapper->toRequestData($request);

        /** @var CategoryResponse $response */
        $response = $this->mapper->fromResponse($this->client->decodeResponse(
            $this->client->patch($request->getIri(), $requestData)
        ));

        return $response;
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws InvalidRequestException
     */
    public function delete(RequestInterface $request): void
    {
        if (!$request instanceof CategoryRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', CategoryRequest::class)
            );
        }

        if (!$request->getIri()) {
            throw new InvalidRequestException('Category IRI is required for this operation');
        }

        $this->client->delete($request->getIri());
    }
}
