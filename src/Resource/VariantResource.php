<?php

declare(strict_types=1);

namespace Apiera\Sdk\Resource;

use Apiera\Sdk\DTO\QueryParameters;
use Apiera\Sdk\DTO\Request\Variant\VariantRequest;
use Apiera\Sdk\DTO\Response\Variant\VariantCollectionResponse;
use Apiera\Sdk\DTO\Response\Variant\VariantResponse;
use Apiera\Sdk\Exception\InvalidRequestException;
use Apiera\Sdk\Exception\MultipleResourcesFoundException;
use Apiera\Sdk\Exception\ResourceNotFoundException;
use Apiera\Sdk\Interface\ClientInterface;
use Apiera\Sdk\Interface\DataMapperInterface;
use Apiera\Sdk\Interface\DTO\RequestInterface;
use Apiera\Sdk\Interface\NoContextRequestResourceInterface;

/**
 * @author Marie Rinden <marie@shoppingnorge.no>
 * @since 1.0.0
 */
final readonly class VariantResource implements NoContextRequestResourceInterface
{
    private const string ENDPOINT = '/variants';

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
    public function find(RequestInterface $request, ?QueryParameters $params = null): VariantCollectionResponse
    {
        if (!$request instanceof VariantRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', VariantRequest::class)
            );
        }

        if (!$request->getProduct()) {
            throw new InvalidRequestException('Product IRI is required for this operation');
        }

        /** @var VariantCollectionResponse $collectionResponse */
        $collectionResponse = $this->mapper->fromCollectionResponse($this->client->decodeResponse(
            $this->client->get($request->getProduct() . self::ENDPOINT, $params)
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
    public function findOneBy(RequestInterface $request, QueryParameters $params): VariantResponse
    {
        if (!$request instanceof Variantrequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', VariantRequest::class)
            );
        }

        $collection = $this->find($request, $params);

        if ($collection->getLdTotalItems() < 1) {
            throw new ResourceNotFoundException(
                'No variant found matching the given criteria'
            );
        }

        if ($collection->getLdTotalItems() > 1) {
            throw new MultipleResourcesFoundException(
                'Multiple variants found matching the given criteria'
            );
        }

        return $collection->getLdMembers()[0];
    }

    /**
     * @throws InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function get(RequestInterface $request): VariantResponse
    {
        if (!$request instanceof VariantRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', VariantRequest::class)
            );
        }

        if (!$request->getIri()) {
            throw new InvalidRequestException('Variant IRI is required for this operation');
        }

        /** @var VariantResponse $response */
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
    public function create(RequestInterface $request): VariantResponse
    {
        if (!$request instanceof VariantRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', VariantRequest::class)
            );
        }

        if (!$request->getProduct()) {
            throw new InvalidRequestException('Product IRI is required for this operation');
        }

        $requestData = $this->mapper->toRequestData($request);

        /** @var VariantResponse $response */
        $response = $this->mapper->fromResponse($this->client->decodeResponse(
            $this->client->post($request->getProduct() . self::ENDPOINT, $requestData)
        ));

        return $response;
    }

    /**
     * @throws InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function update(RequestInterface $request): VariantResponse
    {
        if (!$request instanceof VariantRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', VariantRequest::class)
            );
        }

        if (!$request->getIri()) {
            throw new InvalidRequestException('Variant IRI is required for this operation');
        }

        $requestData = $this->mapper->toRequestData($request);

        /** @var VariantResponse $response */
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
        if (!$request instanceof VariantRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', VariantRequest::class)
            );
        }

        if (!$request->getIri()) {
            throw new InvalidRequestException('Variant IRI is required for this operation');
        }

        $this->client->delete($request->getIri());
    }
}
