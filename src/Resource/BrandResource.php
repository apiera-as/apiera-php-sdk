<?php

declare(strict_types=1);

namespace Apiera\Sdk\Resource;

use Apiera\Sdk\DTO\QueryParameters;
use Apiera\Sdk\DTO\Request\Brand\BrandRequest;
use Apiera\Sdk\DTO\Response\Brand\BrandCollectionResponse;
use Apiera\Sdk\DTO\Response\Brand\BrandResponse;
use Apiera\Sdk\Exception\InvalidRequestException;
use Apiera\Sdk\Interface\ClientInterface;
use Apiera\Sdk\Interface\DataMapperInterface;
use Apiera\Sdk\Interface\DTO\RequestInterface;
use Apiera\Sdk\Interface\DTO\ResponseInterface;
use Apiera\Sdk\Interface\RequestResourceInterface;

/**
 * @author Marie Rinden <marie@shoppingnorge.no>
 * @since 1.0.0
 */
final readonly class BrandResource implements RequestResourceInterface
{
    private const string ENDPOINT = '/brands';

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
    public function find(
        RequestInterface $request,
        ?QueryParameters $params = null
    ): BrandCollectionResponse {
        if (!$request instanceof BrandRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', BrandRequest::class)
            );
        }

        if (!$request->getStore()) {
            throw new InvalidRequestException('Store IRI is required for this operation');
        }

        /** @var BrandCollectionResponse $collectionResponse */
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
    public function findOneBy(RequestInterface $request, QueryParameters $params): ResponseInterface
    {
        if (!$request instanceof BrandRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', BrandRequest::class)
            );
        }

        $collection = $this->find($request, $params);

        if ($collection->getLdTotalItems() < 1) {
            throw new InvalidRequestException('No brand found matching the given criteria');
        }

        return $collection->getLdMembers()[0];
    }

    /**
     * @throws InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function get(RequestInterface $request): BrandResponse
    {
        if (!$request instanceof BrandRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', BrandRequest::class)
            );
        }

        if (!$request->getIri()) {
            throw new InvalidRequestException('Brand IRI is required for this operation');
        }

        /** @var BrandResponse $response */
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
    public function create(RequestInterface $request): BrandResponse
    {
        if (!$request instanceof BrandRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', BrandRequest::class)
            );
        }

        if (!$request->getStore()) {
            throw new InvalidRequestException('Store IRI is required for this operation');
        }

        $requestData = $this->mapper->toRequestData($request);

        /** @var BrandResponse $response */
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
    public function update(RequestInterface $request): BrandResponse
    {
        if (!$request instanceof BrandRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', BrandRequest::class)
            );
        }

        if (!$request->getIri()) {
            throw new InvalidRequestException('Brand IRI is required for this operation');
        }

        $requestData = $this->mapper->toRequestData($request);

        /** @var BrandResponse $response */
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
        if (!$request instanceof Brandrequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', BrandRequest::class)
            );
        }

        if (!$request->getIri()) {
            throw new InvalidRequestException('Brand IRI is required for this operation');
        }

        $this->client->delete($request->getIri());
    }
}
