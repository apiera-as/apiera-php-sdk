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
 * @since 0.3.0
 */
final readonly class BrandResource implements RequestResourceInterface
{
    private const string ENDPOINT = '/brand';

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
    ): BrandCollectionResponse {
        if (!$request instanceof BrandRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', BrandRequest::class)
            );
        }

        /** @var BrandCollectionResponse $collectionResponse */
        $collectionResponse = $this->mapper->fromCollectionResponse($this->client->decodeResponse(
            $this->client->get(self::ENDPOINT, $params)
        ));

        return $collectionResponse;
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws InvalidRequestException
    */
    public function findOneBy(RequestInterface $request, QueryParameters $params): ResponseInterface
    {
        if (!$request instanceof BrandRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', BrandRequest::class)
            );
        }

        $collection = $this->find($request, $params);

        if ($collection->getTotalItems() < 1) {
            throw new InvalidRequestException('No alternate identifier found matching the given criteria');
        }

        return $collection->getMembers()[0];
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws InvalidRequestException
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
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws InvalidRequestException
     */
    public function create(RequestInterface $request): BrandResponse
    {
        if (!$request instanceof BrandRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', BrandRequest::class)
            );
        }

        $requestData = $this->mapper->toRequestData($request);

        /** @var BrandResponse $response */
        $response = $this->mapper->fromResponse($this->client->decodeResponse(
            $this->client->post(self::ENDPOINT, $requestData)
        ));

        return $response;
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws InvalidRequestException
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
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws InvalidRequestException
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
