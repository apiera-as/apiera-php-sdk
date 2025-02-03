<?php

declare(strict_types=1);

namespace Apiera\Sdk\Resource;

use Apiera\Sdk\DTO\QueryParameters;
use Apiera\Sdk\DTO\Request\Property\PropertyRequest;
use Apiera\Sdk\DTO\Response\Property\PropertyCollectionResponse;
use Apiera\Sdk\DTO\Response\Property\PropertyResponse;
use Apiera\Sdk\Exception\InvalidRequestException;
use Apiera\Sdk\Interface\ClientInterface;
use Apiera\Sdk\Interface\DataMapperInterface;
use Apiera\Sdk\Interface\DTO\RequestInterface;
use Apiera\Sdk\Interface\RequestResourceInterface;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 0.3.0
 */
final readonly class PropertyResource implements RequestResourceInterface
{
    private const string ENDPOINT = '/properties';

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
    public function find(RequestInterface $request, ?QueryParameters $params = null): PropertyCollectionResponse
    {
        if (!$request instanceof PropertyRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', PropertyRequest::class)
            );
        }

        if (!$request->getStore()) {
            throw new InvalidRequestException('Store IRI is required for this operation');
        }

        /** @var PropertyCollectionResponse $collectionResponse */
        $collectionResponse = $this->mapper->fromCollectionResponse($this->client->decodeResponse(
            $this->client->get($request->getStore() . self::ENDPOINT, $params)
        ));

        return $collectionResponse;
    }

    /**
     * @throws InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function findOneBy(RequestInterface $request, QueryParameters $params): PropertyResponse
    {
        if (!$request instanceof PropertyRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', PropertyRequest::class)
            );
        }

        $collection = $this->find($request, $params);

        if ($collection->getLdTotalItems() < 1) {
            throw new InvalidRequestException('No property found matching the given criteria');
        }

        return $collection->getLdMembers()[0];
    }

    /**
     * @throws InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function get(RequestInterface $request): PropertyResponse
    {
        if (!$request instanceof PropertyRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', PropertyRequest::class)
            );
        }

        if (!$request->getIri()) {
            throw new InvalidRequestException('Property IRI is required for this operation');
        }

        /** @var PropertyResponse $response */
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
    public function create(RequestInterface $request): PropertyResponse
    {
        if (!$request instanceof PropertyRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', PropertyRequest::class)
            );
        }

        if (!$request->getStore()) {
            throw new InvalidRequestException('Store IRI is required for this operation');
        }

        $requestData = $this->mapper->toRequestData($request);

        /** @var PropertyResponse $response */
        $response = $this->mapper->fromResponse($this->client->decodeResponse(
            $this->client->post($request->getStore() . self::ENDPOINT, $requestData)
        ));

        return $response;
    }

    /**
     * @throws InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function update(RequestInterface $request): PropertyResponse
    {
        if (!$request instanceof PropertyRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', PropertyRequest::class)
            );
        }

        if (!$request->getIri()) {
            throw new InvalidRequestException('Property IRI is required for this operation');
        }

        $requestData = $this->mapper->toRequestData($request);

        /** @var PropertyResponse $response */
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
        if (!$request instanceof PropertyRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', PropertyRequest::class)
            );
        }

        if (!$request->getIri()) {
            throw new InvalidRequestException('Property IRI is required for this operation');
        }

        $this->client->delete($request->getIri());
    }
}
