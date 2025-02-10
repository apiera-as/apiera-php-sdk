<?php

declare(strict_types=1);

namespace Apiera\Sdk\Resource;

use Apiera\Sdk\DTO\QueryParameters;
use Apiera\Sdk\DTO\Request\Sku\SkuRequest;
use Apiera\Sdk\DTO\Response\Sku\SkuCollectionResponse;
use Apiera\Sdk\DTO\Response\Sku\SkuResponse;
use Apiera\Sdk\Exception\InvalidRequestException;
use Apiera\Sdk\Interface\ClientInterface;
use Apiera\Sdk\Interface\DataMapperInterface;
use Apiera\Sdk\Interface\DTO\RequestInterface;
use Apiera\Sdk\Interface\RequestResourceInterface;

/**
 * @author Marie Rinden <marie@shoppingnorge.no>
 * @since 0.3.0
 */
final readonly class SkuResource implements RequestResourceInterface
{
    private const string ENDPOINT = '/api/v1/skus';

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
    public function find(RequestInterface $request, ?QueryParameters $params = null): SkuCollectionResponse
    {
        if (!$request instanceof SkuRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', SkuRequest::class)
            );
        }

        /** @var SkuCollectionResponse $collectionResponse */
        $collectionResponse = $this->mapper->fromCollectionResponse($this->client->decodeResponse(
            $this->client->get(self::ENDPOINT, $params)
        ));

        return $collectionResponse;
    }

    /**
     * @throws InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function findOneBy(RequestInterface $request, QueryParameters $params): SkuResponse
    {
        if (!$request instanceof SkuRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', SkuRequest::class)
            );
        }

        $collection = $this->find($request, $params);

        if ($collection->getLdTotalItems() < 1) {
            throw new InvalidRequestException('No sku found matching the given criteria');
        }

        return $collection->getLdMembers()[0];
    }

    /**
     * @throws InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function get(RequestInterface $request): SkuResponse
    {
        if (!$request instanceof SkuRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', SkuRequest::class)
            );
        }

        if (!$request->getIri()) {
            throw new InvalidRequestException('Sku IRI is required for this operation');
        }

        /** @var SkuResponse $response */
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
    public function create(RequestInterface $request): SkuResponse
    {
        if (!$request instanceof SkuRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', SkuRequest::class)
            );
        }

        $requestData = $this->mapper->toRequestData($request);

        /** @var SkuResponse $response */
        $response = $this->mapper->fromResponse($this->client->decodeResponse(
            $this->client->post(self::ENDPOINT, $requestData)
        ));

        return $response;
    }

    /**
     * @throws InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function update(RequestInterface $request): SkuResponse
    {
        if (!$request instanceof SkuRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', SkuRequest::class)
            );
        }

        if (!$request->getIri()) {
            throw new InvalidRequestException('Sku IRI is required for this operation');
        }

        $requestData = $this->mapper->toRequestData($request);

        /** @var SkuResponse $response */
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
        if (!$request instanceof SkuRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', SkuRequest::class)
            );
        }

        if (!$request->getIri()) {
            throw new InvalidRequestException('Sku IRI is required for this operation');
        }

        $this->client->delete($request->getIri());
    }
}
