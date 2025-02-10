<?php

declare(strict_types=1);

namespace Apiera\Sdk\Resource;

use Apiera\Sdk\DTO\QueryParameters;
use Apiera\Sdk\DTO\Request\PropertyTerm\PropertyTermRequest;
use Apiera\Sdk\DTO\Response\PropertyTerm\PropertyTermCollectionResponse;
use Apiera\Sdk\DTO\Response\PropertyTerm\PropertyTermResponse;
use Apiera\Sdk\Exception\InvalidRequestException;
use Apiera\Sdk\Interface\ClientInterface;
use Apiera\Sdk\Interface\DataMapperInterface;
use Apiera\Sdk\Interface\DTO\RequestInterface;
use Apiera\Sdk\Interface\RequestResourceInterface;

/**
 * @author Marie Rinden <marie@shoppingnorge.no>
 * @since 0.3.0
 */
final readonly class PropertyTermResource implements RequestResourceInterface
{
    private const string ENDPOINT = '/terms';

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
    public function find(RequestInterface $request, ?QueryParameters $params = null): PropertyTermCollectionResponse
    {
        if (!$request instanceof PropertyTermRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', PropertyTermRequest::class)
            );
        }

        if (!$request->getProperty()) {
            throw new InvalidRequestException('Store IRI is required for this operation');
        }

        /** @var PropertyTermCollectionResponse $collectionResponse */
        $collectionResponse = $this->mapper->fromCollectionResponse($this->client->decodeResponse(
            $this->client->get($request->getProperty() . self::ENDPOINT, $params)
        ));

        return $collectionResponse;
    }

    /**
     * @throws InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function findOneBy(RequestInterface $request, QueryParameters $params): PropertyTermResponse
    {
        if (!$request instanceof PropertyTermRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', PropertyTermRequest::class)
            );
        }

        $collection = $this->find($request, $params);

        if ($collection->getLdTotalItems() < 1) {
            throw new InvalidRequestException('No property term found matching the given criteria');
        }

        return $collection->getLdMembers()[0];
    }

    /**
     * @throws InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function get(RequestInterface $request): PropertyTermResponse
    {
        if (!$request instanceof PropertyTermRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', PropertyTermRequest::class)
            );
        }

        if (!$request->getIri()) {
            throw new InvalidRequestException('Property term IRI is required for this operation');
        }

        /** @var PropertyTermResponse $response */
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
    public function create(RequestInterface $request): PropertyTermResponse
    {
        if (!$request instanceof PropertyTermRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', PropertyTermRequest::class)
            );
        }

        if (!$request->getProperty()) {
            throw new InvalidRequestException('Store IRI is required for this operation');
        }

        $requestData = $this->mapper->toRequestData($request);

        /** @var PropertyTermResponse $response */
        $response = $this->mapper->fromResponse($this->client->decodeResponse(
            $this->client->post($request->getProperty() . self::ENDPOINT, $requestData)
        ));

        return $response;
    }

    /**
     * @throws InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function update(RequestInterface $request): PropertyTermResponse
    {
        if (!$request instanceof PropertyTermRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', PropertyTermRequest::class)
            );
        }

        if (!$request->getIri()) {
            throw new InvalidRequestException('Property term IRI is required for this operation');
        }

        $requestData = $this->mapper->toRequestData($request);

        /** @var PropertyTermResponse $response */
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
        if (!$request instanceof PropertyTermRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', PropertyTermRequest::class)
            );
        }

        if (!$request->getIri()) {
            throw new InvalidRequestException('Property term IRI is required for this operation');
        }

        $this->client->delete($request->getIri());
    }
}
