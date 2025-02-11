<?php

declare(strict_types=1);

namespace Apiera\Sdk\Resource;

use Apiera\Sdk\DTO\QueryParameters;
use Apiera\Sdk\DTO\Request\Inventory\InventoryRequest;
use Apiera\Sdk\DTO\Response\Inventory\InventoryCollectionResponse;
use Apiera\Sdk\DTO\Response\Inventory\InventoryResponse;
use Apiera\Sdk\Exception\InvalidRequestException;
use Apiera\Sdk\Interface\ClientInterface;
use Apiera\Sdk\Interface\DataMapperInterface;
use Apiera\Sdk\Interface\DTO\RequestInterface;
use Apiera\Sdk\Interface\RequestResourceInterface;

/**int
 * @author Marie Rinden <marie@shoppingnorge.no>
 * @since 0.3.0
 */
final readonly class InventoryResource implements RequestResourceInterface
{
    private const string ENDPOINT = '/inventories';

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
    public function find(RequestInterface $request, ?QueryParameters $params = null): InventoryCollectionResponse
    {
        if (!$request instanceof InventoryRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', InventoryRequest::class)
            );
        }

        if (!$request->getInventoryLocation()) {
            throw new InvalidRequestException('Inventory location IRI is required for this operation');
        }

        /** @var InventoryCollectionResponse $collectionResponse */
        $collectionResponse = $this->mapper->fromCollectionResponse($this->client->decodeResponse(
            $this->client->get($request->getInventoryLocation() . self::ENDPOINT, $params)
        ));

        return $collectionResponse;
    }

    /**
     * @throws InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function findOneBy(RequestInterface $request, QueryParameters $params): InventoryResponse
    {
        if (!$request instanceof InventoryRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', InventoryRequest::class)
            );
        }

        $collection = $this->find($request, $params);

        if ($collection->getLdTotalItems() < 1) {
            throw new InvalidRequestException('No inventory found matching the given criteria');
        }

        return $collection->getLdMembers()[0];
    }

    /**
     * @throws InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function get(RequestInterface $request): InventoryResponse
    {
        if (!$request instanceof InventoryRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', InventoryRequest::class)
            );
        }

        if (!$request->getIri()) {
            throw new InvalidRequestException('Inventory IRI is required for this operation');
        }

        /** @var InventoryResponse $response */
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
    public function create(RequestInterface $request): InventoryResponse
    {
        if (!$request instanceof InventoryRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', InventoryRequest::class)
            );
        }

        if (!$request->getInventoryLocation()) {
            throw new InvalidRequestException('Inventory location IRI is required for this operation');
        }

        $requestData = $this->mapper->toRequestData($request);

        /** @var InventoryResponse $response */
        $response = $this->mapper->fromResponse($this->client->decodeResponse(
            $this->client->post($request->getInventoryLocation() . self::ENDPOINT, $requestData)
        ));

        return $response;
    }

    /**
     * @throws InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function update(RequestInterface $request): InventoryResponse
    {
        if (!$request instanceof InventoryRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', InventoryRequest::class)
            );
        }

        if (!$request->getIri()) {
            throw new InvalidRequestException('Inventory IRI is required for this operation');
        }

        $requestData = $this->mapper->toRequestData($request);

        /** @var InventoryResponse $response */
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
        if (!$request instanceof InventoryRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', InventoryRequest::class)
            );
        }

        if (!$request->getIri()) {
            throw new InvalidRequestException('Inventory IRI is required for this operation');
        }

        $this->client->delete($request->getIri());
    }
}
