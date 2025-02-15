<?php

declare(strict_types=1);

namespace Apiera\Sdk\Resource;

use Apiera\Sdk\DTO\QueryParameters;
use Apiera\Sdk\DTO\Request\Product\ProductRequest;
use Apiera\Sdk\DTO\Response\Product\ProductCollectionResponse;
use Apiera\Sdk\DTO\Response\Product\ProductResponse;
use Apiera\Sdk\Exception\InvalidRequestException;
use Apiera\Sdk\Exception\MultipleResourcesFoundException;
use Apiera\Sdk\Exception\ResourceNotFoundException;
use Apiera\Sdk\Interface\ClientInterface;
use Apiera\Sdk\Interface\ConfigurationInterface;
use Apiera\Sdk\Interface\ContextRequestResourceInterface;
use Apiera\Sdk\Interface\DataMapperInterface;
use Apiera\Sdk\Interface\DTO\RequestInterface;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 1.0.0
 */
final readonly class ProductResource implements ContextRequestResourceInterface
{
    private const string ENDPOINT = '/products';

    public function __construct(
        private ClientInterface $client,
        private DataMapperInterface $mapper,
        private ConfigurationInterface $configuration,
    ) {
    }

    /**
     * @throws InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function find(RequestInterface $request, ?QueryParameters $params = null): ProductCollectionResponse
    {
        if (!$request instanceof ProductRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', ProductRequest::class)
            );
        }

        $store = $request->getStore() ?? $this->configuration->getDefaultStore();

        if ($store === null) {
            throw new InvalidRequestException('Store IRI is required for this operation');
        }

        /** @var ProductCollectionResponse $collectionResponse */
        $collectionResponse = $this->mapper->fromCollectionResponse($this->client->decodeResponse(
            $this->client->get($store . self::ENDPOINT, $params)
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
    public function findOneBy(RequestInterface $request, QueryParameters $params): ProductResponse
    {
        if (!$request instanceof ProductRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', ProductRequest::class)
            );
        }

        $collection = $this->find($request, $params);

        if ($collection->getLdTotalItems() < 1) {
            throw new ResourceNotFoundException(
                'No product found matching the given criteria'
            );
        }

        if ($collection->getLdTotalItems() > 1) {
            throw new MultipleResourcesFoundException(
                'Multiple products found matching the given criteria'
            );
        }

        return $collection->getLdMembers()[0];
    }

    /**
     * @throws InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function get(RequestInterface $request): ProductResponse
    {
        if (!$request instanceof ProductRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', ProductRequest::class)
            );
        }

        if (!$request->getIri()) {
            throw new InvalidRequestException('Product IRI is required for this operation');
        }

        /** @var ProductResponse $response */
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
    public function create(RequestInterface $request): ProductResponse
    {
        if (!$request instanceof ProductRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', ProductRequest::class)
            );
        }

        $store = $request->getStore() ?? $this->configuration->getDefaultStore();

        if ($store === null) {
            throw new InvalidRequestException('Store IRI is required for this operation');
        }

        $requestData = $this->mapper->toRequestData($request);

        /** @var ProductResponse $response */
        $response = $this->mapper->fromResponse($this->client->decodeResponse(
            $this->client->post($store . self::ENDPOINT, $requestData)
        ));

        return $response;
    }

    /**
     * @throws InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function update(RequestInterface $request): ProductResponse
    {
        if (!$request instanceof ProductRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', ProductRequest::class)
            );
        }

        if (!$request->getIri()) {
            throw new InvalidRequestException('Product IRI is required for this operation');
        }

        $requestData = $this->mapper->toRequestData($request);

        /** @var ProductResponse $response */
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
        if (!$request instanceof ProductRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', ProductRequest::class)
            );
        }

        if (!$request->getIri()) {
            throw new InvalidRequestException('Product IRI is required for this operation');
        }

        $this->client->delete($request->getIri());
    }
}
