<?php

declare(strict_types=1);

namespace Apiera\Sdk\Resource;

use Apiera\Sdk\DTO\QueryParameters;
use Apiera\Sdk\DTO\Request\InventoryLocation\InventoryLocationRequest;
use Apiera\Sdk\DTO\Response\InventoryLocation\InventoryLocationCollectionResponse;
use Apiera\Sdk\DTO\Response\InventoryLocation\InventoryLocationResponse;
use Apiera\Sdk\Exception\InvalidRequestException;
use Apiera\Sdk\Interface\ClientInterface;
use Apiera\Sdk\Interface\DataMapperInterface;
use Apiera\Sdk\Interface\DTO\RequestInterface;
use Apiera\Sdk\Interface\RequestResourceInterface;

/**
 * @author Marie Rinden <marie@shoppingnorge.no>
 * @since 0.3.0
 */
final readonly class InventoryLocationRecource implements RequestResourceInterface
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
    public function find(
        RequestInterface $request,
        ?QueryParameters $params = null
    ): InventoryLocationCollectionResponse {
        if (!$request instanceof InventoryLocationRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', InventoryLocationRequest::class)
            );
        }

        if (!$request->getStore()) {
            throw new InvalidRequestException('Store IRI is required for this operation');
        }

        /** @var InventoryLocationCollectionResponse $collectionResponse */
        $collectionResponse = $this->mapper->fromCollectionResponse($this->client->decodeResponse(
            $this->client->get($request->getStore() . self::ENDPOINT, $params)
        ));

        return $collectionResponse;
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws InvalidRequestException
     */
    public function findOneBy(RequestInterface $request, QueryParameters $params): InventoryLocationResponse
    {
        if (!$request instanceof InventoryLocationRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', InventoryLocationRequest::class)
            );
        }

        $collection = $this->find($request, $params);

        if ($collection->getTotalItems() < 1) {
            throw new InvalidRequestException('No inventory location found matching the given criteria');
        }

        return $collection->getMembers()[0];
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws InvalidRequestException
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
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws InvalidRequestException
     */
    public function create(RequestInterface $request): InventoryLocationResponse
    {
        if (!$request instanceof InventoryLocationRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', InventoryLocationRequest::class)
            );
        }

        if (!$request->getStore()) {
            throw new InvalidRequestException('Store IRI is required for this operation');
        }

        $requestData = $this->mapper->toRequestData($request);

        /** @var InventoryLocationResponse $response */
        $response = $this->mapper->fromResponse($this->client->decodeResponse(
            $this->client->post($request->getStore() . self::ENDPOINT, $requestData)
        ));

        return $response;
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws InvalidRequestException
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
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws InvalidRequestException
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
