<?php

declare(strict_types=1);

namespace Apiera\Sdk\Resource;

use Apiera\Sdk\DTO\QueryParameters;
use Apiera\Sdk\DTO\Request\ResourceMap\ResourceMapRequest;
use Apiera\Sdk\DTO\Response\ResourceMap\ResourceMapCollectionResponse;
use Apiera\Sdk\DTO\Response\ResourceMap\ResourceMapResponse;
use Apiera\Sdk\Exception\InvalidRequestException;
use Apiera\Sdk\Interface\ClientInterface;
use Apiera\Sdk\Interface\DataMapperInterface;
use Apiera\Sdk\Interface\DTO\RequestInterface;
use Apiera\Sdk\Interface\RequestResourceInterface;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 1.0.0
 */
final readonly class ResourceMapResource implements RequestResourceInterface
{
    private const string ENDPOINT = '/mappings';

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
    public function find(RequestInterface $request, ?QueryParameters $params = null): ResourceMapCollectionResponse
    {
        if (!$request instanceof ResourceMapRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', ResourceMapRequest::class)
            );
        }

        if (!$request->getIntegration()) {
            throw new InvalidRequestException('Integration IRI is required for this operation');
        }

        /** @var ResourceMapCollectionResponse $collectionResponse */
        $collectionResponse = $this->mapper->fromCollectionResponse($this->client->decodeResponse(
            $this->client->get($request->getIntegration() . self::ENDPOINT, $params)
        ));

        return $collectionResponse;
    }

    /**
     * @throws InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function findOneBy(RequestInterface $request, QueryParameters $params): ResourceMapResponse
    {
        if (!$request instanceof ResourceMapRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', ResourceMapRequest::class)
            );
        }

        $collection = $this->find($request, $params);

        if ($collection->getLdTotalItems() < 1) {
            throw new InvalidRequestException('No resource map found matching the given criteria');
        }

        return $collection->getLdMembers()[0];
    }

    /**
     * @throws InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function get(RequestInterface $request): ResourceMapResponse
    {
        if (!$request instanceof ResourceMapRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', ResourceMapRequest::class)
            );
        }

        if (!$request->getIri()) {
            throw new InvalidRequestException('Resource map IRI is required for this operation');
        }

        /** @var ResourceMapResponse $response */
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
    public function create(RequestInterface $request): ResourceMapResponse
    {
        if (!$request instanceof ResourceMapRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', ResourceMapRequest::class)
            );
        }

        if (!$request->getIntegration()) {
            throw new InvalidRequestException('Integration IRI is required for this operation');
        }

        $requestData = $this->mapper->toRequestData($request);

        /** @var ResourceMapResponse $response */
        $response = $this->mapper->fromResponse($this->client->decodeResponse(
            $this->client->post($request->getIntegration() . self::ENDPOINT, $requestData)
        ));

        return $response;
    }

    /**
     * @throws InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function update(RequestInterface $request): ResourceMapResponse
    {
        if (!$request instanceof ResourceMapRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', ResourceMapRequest::class)
            );
        }

        if (!$request->getIri()) {
            throw new InvalidRequestException('Resource map IRI is required for this operation');
        }

        $requestData = $this->mapper->toRequestData($request);

        /** @var ResourceMapResponse $response */
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
        if (!$request instanceof ResourceMapRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', ResourceMapRequest::class)
            );
        }

        if (!$request->getIri()) {
            throw new InvalidRequestException('Resource map IRI is required for this operation');
        }

        $this->client->delete($request->getIri());
    }
}
