<?php

declare(strict_types=1);

namespace Apiera\Sdk\Resource;

use Apiera\Sdk\DTO\QueryParameters;
use Apiera\Sdk\DTO\Request\InventoryLocation\InventoryLocationRequest;
use Apiera\Sdk\DTO\Response\InventoryLocation\InventoryLocationCollectionResponse;
use Apiera\Sdk\DTO\Response\InventoryLocation\InventoryLocationResponse;
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
final readonly class InventoryLocationResource implements RequestResourceInterface
{
    private const string ENDPOINT = '/api/v1/inventory_locations';

    public function __construct(
        private ClientInterface $client,
        private DataMapperInterface $mapper
    ) {
    }

    /**
     * @throws InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function find(
        RequestInterface $request,
        ?QueryParameters $params = null
    ): InventoryLocationCollectionResponse {
        if (!$request instanceof InventoryLocationRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', InventoryLocationRequest::class)
            );
        }

        /** @var InventoryLocationCollectionResponse $collectionResponse */
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
    public function findOneBy(RequestInterface $request, QueryParameters $params): InventoryLocationResponse
    {
        if (!$request instanceof InventoryLocationRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', InventoryLocationRequest::class)
            );
        }

        $collection = $this->find($request, $params);

        if ($collection->getLdTotalItems() < 1) {
            throw new ResourceNotFoundException(
                'No inventory location found matching the given criteria'
            );
        }

        if ($collection->getLdTotalItems() > 1) {
            throw new MultipleResourcesFoundException(
                'Multiple inventory locations found matching the given criteria'
            );
        }

        return $collection->getLdMembers()[0];
    }

    /**
     * @throws InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function get(RequestInterface $request): InventoryLocationResponse
    {
        if (!$request instanceof InventoryLocationRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', InventoryLocationRequest::class)
            );
        }

        if (!$request->getIri()) {
            throw new InvalidRequestException('Inventory location IRI is required for this operation');
        }

        /** @var InventoryLocationResponse $response */
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
    public function create(RequestInterface $request): InventoryLocationResponse
    {
        if (!$request instanceof InventoryLocationRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', InventoryLocationRequest::class)
            );
        }

        $requestData = $this->mapper->toRequestData($request);

        /** @var InventoryLocationResponse $response */
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
    public function update(RequestInterface $request): InventoryLocationResponse
    {
        if (!$request instanceof InventoryLocationRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', InventoryLocationRequest::class)
            );
        }

        if (!$request->getIri()) {
            throw new InvalidRequestException('Inventory location IRI is required for this operation');
        }

        $requestData = $this->mapper->toRequestData($request);

        /** @var InventoryLocationResponse $response */
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
        if (!$request instanceof InventoryLocationRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', InventoryLocationRequest::class)
            );
        }

        if (!$request->getIri()) {
            throw new InvalidRequestException('Inventory location IRI is required for this operation');
        }

        $this->client->delete($request->getIri());
    }
}
