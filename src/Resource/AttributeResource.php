<?php

declare(strict_types=1);

namespace Apiera\Sdk\Resource;

use Apiera\Sdk\DTO\QueryParameters;
use Apiera\Sdk\DTO\Request\Attribute\AttributeRequest;
use Apiera\Sdk\DTO\Response\Attribute\AttributeCollectionResponse;
use Apiera\Sdk\DTO\Response\Attribute\AttributeResponse;
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
 * @since 0.2.0
 */
final readonly class AttributeResource implements ContextRequestResourceInterface
{
    private const string ENDPOINT = '/attributes';

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
    public function find(RequestInterface $request, ?QueryParameters $params = null): AttributeCollectionResponse
    {
        if (!$request instanceof AttributeRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', AttributeRequest::class)
            );
        }

        $store = $request->getStore() ?? $this->configuration->getDefaultStore();

        if ($store === null) {
            throw new InvalidRequestException('Store IRI is required for this operation');
        }

        /** @var AttributeCollectionResponse $collectionResponse */
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
    public function findOneBy(RequestInterface $request, QueryParameters $params): AttributeResponse
    {
        if (!$request instanceof AttributeRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', AttributeRequest::class)
            );
        }

        $collection = $this->find($request, $params);

        if ($collection->getLdTotalItems() < 1) {
            throw new ResourceNotFoundException(
                'No attribute found matching the given criteria'
            );
        }

        if ($collection->getLdTotalItems() > 1) {
            throw new MultipleResourcesFoundException(
                'Multiple attributes found matching the given criteria'
            );
        }

        return $collection->getLdMembers()[0];
    }

    /**
     * @throws InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function get(RequestInterface $request): AttributeResponse
    {
        if (!$request instanceof AttributeRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', AttributeRequest::class)
            );
        }

        if (!$request->getIri()) {
            throw new InvalidRequestException('Attribute IRI is required for this operation');
        }

        /** @var AttributeResponse $response */
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
    public function create(RequestInterface $request): AttributeResponse
    {
        if (!$request instanceof AttributeRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', AttributeRequest::class)
            );
        }

        $store = $request->getStore() ?? $this->configuration->getDefaultStore();

        if ($store === null) {
            throw new InvalidRequestException('Store IRI is required for this operation');
        }

        $requestData = $this->mapper->toRequestData($request);

        /** @var AttributeResponse $response */
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
    public function update(RequestInterface $request): AttributeResponse
    {
        if (!$request instanceof AttributeRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', AttributeRequest::class)
            );
        }

        if (!$request->getIri()) {
            throw new InvalidRequestException('Attribute IRI is required for this operation');
        }

        $requestData = $this->mapper->toRequestData($request);

        /** @var AttributeResponse $response */
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
        if (!$request instanceof AttributeRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', AttributeRequest::class)
            );
        }

        if (!$request->getIri()) {
            throw new InvalidRequestException('Attribute IRI is required for this operation');
        }

        $this->client->delete($request->getIri());
    }
}
