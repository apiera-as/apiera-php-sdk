<?php

declare(strict_types=1);

namespace Apiera\Sdk\Resource;

use Apiera\Sdk\DTO\QueryParameters;
use Apiera\Sdk\DTO\Request\Distributor\DistributorRequest;
use Apiera\Sdk\DTO\Response\Distributor\DistributorCollectionResponse;
use Apiera\Sdk\DTO\Response\Distributor\DistributorResponse;
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
final readonly class DistributorResource implements ContextRequestResourceInterface
{
    private const string ENDPOINT = '/distributors';

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
    public function find(RequestInterface $request, ?QueryParameters $params = null): DistributorCollectionResponse
    {
        if (!$request instanceof DistributorRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', DistributorRequest::class)
            );
        }

        $store = $request->getStore() ?? $this->configuration->getDefaultStore();

        if ($store === null) {
            throw new InvalidRequestException('Store IRI is required for this operation');
        }

        /** @var DistributorCollectionResponse $collectionResponse */
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
    public function findOneBy(RequestInterface $request, QueryParameters $params): DistributorResponse
    {
        if (!$request instanceof DistributorRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', DistributorRequest::class)
            );
        }

        $collection = $this->find($request, $params);

        if ($collection->getLdTotalItems() < 1) {
            throw new ResourceNotFoundException(
                'No distributor found matching the given criteria'
            );
        }

        if ($collection->getLdTotalItems() > 1) {
            throw new MultipleResourcesFoundException(
                'Multiple distributors found matching the given criteria'
            );
        }

        return $collection->getLdMembers()[0];
    }

    /**
     * @throws InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function get(RequestInterface $request): DistributorResponse
    {
        if (!$request instanceof DistributorRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', DistributorRequest::class)
            );
        }

        if (!$request->getIri()) {
            throw new InvalidRequestException('Distributor IRI is required for this operation');
        }

        /** @var DistributorResponse $response */
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
    public function create(RequestInterface $request): DistributorResponse
    {
        if (!$request instanceof DistributorRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', DistributorRequest::class)
            );
        }

        $store = $request->getStore() ?? $this->configuration->getDefaultStore();

        if ($store === null) {
            throw new InvalidRequestException('Store IRI is required for this operation');
        }

        $requestData = $this->mapper->toRequestData($request);

        /** @var DistributorResponse $response */
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
    public function update(RequestInterface $request): DistributorResponse
    {
        if (!$request instanceof DistributorRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', DistributorRequest::class)
            );
        }

        if (!$request->getIri()) {
            throw new InvalidRequestException('Distributor IRI is required for this operation');
        }

        $requestData = $this->mapper->toRequestData($request);

        /** @var DistributorResponse $response */
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
        if (!$request instanceof DistributorRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', DistributorRequest::class)
            );
        }

        if (!$request->getIri()) {
            throw new InvalidRequestException('Distributor IRI is required for this operation');
        }

        $this->client->delete($request->getIri());
    }
}
