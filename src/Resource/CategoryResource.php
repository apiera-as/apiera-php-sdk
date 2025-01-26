<?php

declare(strict_types=1);

namespace Apiera\Sdk\Resource;

use Apiera\Sdk\Client;
use Apiera\Sdk\DataMapper\CategoryDataMapper;
use Apiera\Sdk\DTO\QueryParameters;
use Apiera\Sdk\DTO\Request\Category\CategoryRequest;
use Apiera\Sdk\DTO\Response\Category\CategoryCollectionResponse;
use Apiera\Sdk\DTO\Response\Category\CategoryResponse;
use Apiera\Sdk\Exception\InvalidRequestException;
use Apiera\Sdk\Interface\ClientExceptionInterface;
use Apiera\Sdk\Interface\ClientInterface;
use Apiera\Sdk\Interface\DataMapperInterface;
use Apiera\Sdk\Interface\DTO\RequestInterface;
use Apiera\Sdk\Interface\DTO\ResponseInterface;
use Apiera\Sdk\Interface\RequestResourceInterface;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @package Apiera\Sdk\Resource
 * @since 0.1.0
 */
final readonly class CategoryResource implements RequestResourceInterface
{
    private const string ENDPOINT = '/categories';

    /**
     * @param Client $client
     * @param CategoryDataMapper $mapper
     */
    public function __construct(
        private ClientInterface $client,
        private DataMapperInterface $mapper,
    ) {
    }

    /**
     * @param CategoryRequest $request
     * @param QueryParameters|null $params
     * @return CategoryCollectionResponse
     * @throws ClientExceptionInterface
     * @throws InvalidRequestException
     */
    public function find(RequestInterface $request, ?QueryParameters $params = null): CategoryCollectionResponse
    {
        if (!$request->getStore()) {
            throw new InvalidRequestException('Store IRI is required for this operation');
        }

        return $this->mapper->fromCollectionResponse($this->client->decodeResponse(
            $this->client->get($request->getStore() . self::ENDPOINT, $params)
        ));
    }

    /**
     * @param CategoryRequest $request
     * @param QueryParameters $params
     * @return CategoryResponse
     * @throws ClientExceptionInterface
     * @throws InvalidRequestException
     */
    public function findOneBy(RequestInterface $request, QueryParameters $params): ResponseInterface
    {
        $collection = $this->find($request, $params);

        if ($collection->getTotalItems() < 1) {
            throw new InvalidRequestException('No category found matching the given criteria');
        }

        return $collection->getMembers()[0];
    }

    /**
     * @param CategoryRequest $request
     * @return CategoryResponse
     * @throws ClientExceptionInterface
     * @throws InvalidRequestException
     */
    public function get(RequestInterface $request): ResponseInterface
    {
        if (!$request->getIri()) {
            throw new InvalidRequestException('Category IRI is required for this operation');
        }

        return $this->mapper->fromResponse($this->client->decodeResponse(
            $this->client->get($request->getIri())
        ));
    }

    /**
     * @param CategoryRequest $request
     * @return CategoryResponse
     * @throws ClientExceptionInterface
     * @throws InvalidRequestException
     */
    public function create(RequestInterface $request): ResponseInterface
    {
        if (!$request->getStore()) {
            throw new InvalidRequestException('Store IRI is required for this operation');
        }

        $requestData = $this->mapper->toRequestData($request);

        return $this->mapper->fromResponse($this->client->decodeResponse(
            $this->client->post($request->getStore() . self::ENDPOINT, $requestData)
        ));
    }

    /**
     * @param CategoryRequest $request
     * @return CategoryResponse
     * @throws ClientExceptionInterface
     * @throws InvalidRequestException
     */
    public function update(RequestInterface $request): ResponseInterface
    {
        if (!$request->getIri()) {
            throw new InvalidRequestException('Category IRI is required for this operation');
        }

        $requestData = $this->mapper->toRequestData($request);

        return $this->mapper->fromResponse($this->client->decodeResponse(
            $this->client->patch($request->getIri(), $requestData)
        ));
    }

    /**
     * @param CategoryRequest $request
     * @return void
     * @throws ClientExceptionInterface
     * @throws InvalidRequestException
     */
    public function delete(RequestInterface $request): void
    {
        if (!$request->getIri()) {
            throw new InvalidRequestException('Category IRI is required for this operation');
        }

        $this->client->delete($request->getIri());
    }
}
