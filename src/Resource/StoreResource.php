<?php

declare(strict_types=1);

namespace Apiera\Sdk\Resource;

use Apiera\Sdk\DTO\QueryParameters;
use Apiera\Sdk\DTO\Request\Store\StoreRequest;
use Apiera\Sdk\DTO\Response\Store\StoreCollectionResponse;
use Apiera\Sdk\DTO\Response\Store\StoreResponse;
use Apiera\Sdk\Exception\InvalidRequestException;
use Apiera\Sdk\Exception\MultipleResourcesFoundException;
use Apiera\Sdk\Exception\ResourceNotFoundException;
use Apiera\Sdk\Interface\ClientInterface;
use Apiera\Sdk\Interface\DataMapperInterface;
use Apiera\Sdk\Interface\DTO\RequestInterface;
use Apiera\Sdk\Interface\RequestResourceInterface;

/**
 * @author Marie Rinden <marie@shoppingnorge.no>
 * @since 1.0.0
 */
final readonly class StoreResource implements RequestResourceInterface
{
    private const string ENDPOINT = '/api/v1/stores';

    public function __construct(
        private ClientInterface $client,
        private DataMapperInterface $mapper,
    ) {
    }

    /**
     * @throws InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function find(RequestInterface $request, ?QueryParameters $params = null): StoreCollectionResponse
    {
        if (!$request instanceof StoreRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', Storerequest::class)
            );
        }

        /** @var StoreCollectionResponse $collectionResponse */
        $collectionResponse = $this->mapper->fromCollectionResponse($this->client->decodeResponse(
            $this->client->get(self::ENDPOINT, $params)
        ));

        return $collectionResponse;
    }

    /**
     * @throws InvalidRequestException
     * @throws ResourceNotFoundException
     * @throws MultipleResourcesFoundException
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function findOneBy(RequestInterface $request, QueryParameters $params): StoreResponse
    {
        if (!$request instanceof StoreRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', StoreRequest::class)
            );
        }

        $collection = $this->find($request, $params);

        if ($collection->getLdTotalItems() < 1) {
            throw new ResourceNotFoundException(
                'No store found matching the given criteria'
            );
        }

        if ($collection->getLdTotalItems() > 1) {
            throw new MultipleResourcesFoundException(
                'Multiple stores found matching the given criteria'
            );
        }

        return $collection->getLdMembers()[0];
    }

    /**
     * @throws InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function get(RequestInterface $request): StoreResponse
    {
        if (!$request instanceof StoreRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', StoreRequest::class)
            );
        }

        if (!$request->getIri()) {
            throw new InvalidRequestException('Store IRI is required for this operation');
        }

        /** @var StoreResponse $response */
        $response = $this->mapper->fromResponse($this->client->decodeResponse(
            $this->client->get($request->getIri())
        ));

        return $response;
    }

    /**
     * @throws InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function create(RequestInterface $request): StoreResponse
    {
        if (!$request instanceof StoreRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', StoreRequest::class)
            );
        }

        $requestData = $this->mapper->toRequestData($request);

        /** @var StoreResponse $response */
        $response = $this->mapper->fromResponse($this->client->decodeResponse(
            $this->client->post(self::ENDPOINT, $requestData)
        ));

        return $response;
    }

    /**
     * @throws InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function update(RequestInterface $request): StoreResponse
    {
        if (!$request instanceof StoreRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', StoreRequest::class)
            );
        }

        if (!$request->getIri()) {
            throw new InvalidRequestException('Store IRI is required for this operation');
        }

        $requestData = $this->mapper->toRequestData($request);

        /** @var StoreResponse $response */
        $response = $this->mapper->fromResponse($this->client->decodeResponse(
            $this->client->patch($request->getIri(), $requestData)
        ));

        return $response;
    }

    /**
     * @throws InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     */
    public function delete(RequestInterface $request): void
    {
        if (!$request instanceof StoreRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', StoreRequest::class)
            );
        }

        if (!$request->getIri()) {
            throw new InvalidRequestException('Store IRI is required for this operation');
        }

        $this->client->delete($request->getIri());
    }
}
