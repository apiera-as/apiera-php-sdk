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
    ): InventoryCollectionResponse {
        if (!$request instanceof InventoryRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', InventoryRequest::class)
            );
        }

        /** @var InventoryCollectionResponse $collectionResponse */
        $collectionResponse = $this->mapper->fromCollectionResponse($this->client->decodeResponse(
            $this->client->get(self::ENDPOINT, $params)
        ));

        return $collectionResponse;
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws InvalidRequestException
     */
    public function findOneBy(RequestInterface $request, QueryParameters $params): InventoryResponse
    {
        if (!$request instanceof InventoryRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', InventoryRequest::class)
            );
        }

        $collection = $this->find($request, $params);

        if ($collection->getTotalItems() < 1) {
            throw new InvalidRequestException('No inventory found matching the given criteria');
        }

        return $collection->getMembers()[0];
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws InvalidRequestException
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
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws InvalidRequestException
     */
    public function create(RequestInterface $request): InventoryResponse
    {
        if (!$request instanceof InventoryRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', InventoryRequest::class)
            );
        }

        $requestData = $this->mapper->toRequestData($request);

        /** @var InventoryResponse $response */
        $response = $this->mapper->fromResponse($this->client->decodeResponse(
            $this->client->post(self::ENDPOINT, $requestData)
        ));

        return $response;
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws InvalidRequestException
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
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws InvalidRequestException
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
