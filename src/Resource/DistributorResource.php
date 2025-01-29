<?php

declare(strict_types=1);

namespace Apiera\Sdk\Resource;

use Apiera\Sdk\DTO\QueryParameters;
use Apiera\Sdk\DTO\Request\Distributor\DistributorRequest;
use Apiera\Sdk\DTO\Response\Distributor\DistributorCollectionResponse;
use Apiera\Sdk\DTO\Response\Distributor\DistributorResponse;
use Apiera\Sdk\Exception\InvalidRequestException;
use Apiera\Sdk\Interface\ClientInterface;
use Apiera\Sdk\Interface\DataMapperInterface;
use Apiera\Sdk\Interface\DTO\RequestInterface;
use Apiera\Sdk\Interface\RequestResourceInterface;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 0.3.0
 */
final readonly class DistributorResource implements RequestResourceInterface
{
    private const string ENDPOINT = '/distributors';

    public function __construct(
        private ClientInterface $client,
        private DataMapperInterface $mapper,
    ) {
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws InvalidRequestException
     */
    public function find(RequestInterface $request, ?QueryParameters $params = null): DistributorCollectionResponse
    {
        if (!$request instanceof DistributorRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', DistributorRequest::class)
            );
        }

        if (!$request->getStore()) {
            throw new InvalidRequestException('Store IRI is required for this operation');
        }

        /** @var DistributorCollectionResponse $collectionResponse */
        $collectionResponse = $this->mapper->fromCollectionResponse($this->client->decodeResponse(
            $this->client->get($request->getStore() . self::ENDPOINT, $params)
        ));

        return $collectionResponse;
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws InvalidRequestException
     */
    public function findOneBy(RequestInterface $request, QueryParameters $params): DistributorResponse
    {
        if (!$request instanceof DistributorRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', DistributorRequest::class)
            );
        }

        $collection = $this->find($request, $params);

        if ($collection->getTotalItems() < 1) {
            throw new InvalidRequestException('No distributor found matching the given criteria');
        }

        return $collection->getMembers()[0];
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws InvalidRequestException
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
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws InvalidRequestException
     */
    public function create(RequestInterface $request): DistributorResponse
    {
        if (!$request instanceof DistributorRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', DistributorRequest::class)
            );
        }

        if (!$request->getStore()) {
            throw new InvalidRequestException('Store IRI is required for this operation');
        }

        $requestData = $this->mapper->toRequestData($request);

        /** @var DistributorResponse $response */
        $response = $this->mapper->fromResponse($this->client->decodeResponse(
            $this->client->post($request->getStore() . self::ENDPOINT, $requestData)
        ));

        return $response;
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws InvalidRequestException
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
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws InvalidRequestException
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
