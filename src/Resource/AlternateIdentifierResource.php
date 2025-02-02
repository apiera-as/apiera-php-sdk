<?php

declare(strict_types=1);

namespace Apiera\Sdk\Resource;

use Apiera\Sdk\DTO\QueryParameters;
use Apiera\Sdk\DTO\Request\AlternateIdentifier\AlternateIdentifierRequest;
use Apiera\Sdk\DTO\Response\AlternateIdentifier\AlternateIdentifierCollectionResponse;
use Apiera\Sdk\DTO\Response\AlternateIdentifier\AlternateIdentifierResponse;
use Apiera\Sdk\Exception\InvalidRequestException;
use Apiera\Sdk\Interface\ClientInterface;
use Apiera\Sdk\Interface\DataMapperInterface;
use Apiera\Sdk\Interface\DTO\RequestInterface;
use Apiera\Sdk\Interface\RequestResourceInterface;

/**
 * @author Marie Rinden <marie@shoppingnorge.no>
 * @since 0.2.0
 */
final readonly class AlternateIdentifierResource implements RequestResourceInterface
{
    private const string ENDPOINT = '/api/v1/alternate_identifiers';

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
    ): AlternateIdentifierCollectionResponse {
        if (!$request instanceof AlternateIdentifierRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', AlternateIdentifierRequest::class)
            );
        }

        /** @var AlternateIdentifierCollectionResponse $collectionResponse */
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
    public function findOneBy(RequestInterface $request, QueryParameters $params): AlternateIdentifierResponse
    {
        if (!$request instanceof AlternateIdentifierRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', AlternateIdentifierRequest::class)
            );
        }

        $collection = $this->find($request, $params);

        if ($collection->getTotalItems() < 1) {
            throw new InvalidRequestException('No alternate identifier found matching the given criteria');
        }

        return $collection->getMembers()[0];
    }

    /**
     * @throws InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function get(RequestInterface $request): AlternateIdentifierResponse
    {
        if (!$request instanceof AlternateIdentifierRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', AlternateIdentifierRequest::class)
            );
        }

        if (!$request->getIri()) {
            throw new InvalidRequestException('Alternate identifier IRI is required for this operation');
        }

        /** @var AlternateIdentifierResponse $response */
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
    public function create(RequestInterface $request): AlternateIdentifierResponse
    {
        if (!$request instanceof AlternateIdentifierRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', AlternateIdentifierRequest::class)
            );
        }

        $requestData = $this->mapper->toRequestData($request);

        /** @var AlternateIdentifierResponse $response */
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
    public function update(RequestInterface $request): AlternateIdentifierResponse
    {
        if (!$request instanceof AlternateIdentifierRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', AlternateIdentifierRequest::class)
            );
        }

        if (!$request->getIri()) {
            throw new InvalidRequestException('Alternate identifier IRI is required for this operation');
        }

        $requestData = $this->mapper->toRequestData($request);

        /** @var AlternateIdentifierResponse $response */
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
        if (!$request instanceof AlternateIdentifierRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', AlternateIdentifierRequest::class)
            );
        }

        if (!$request->getIri()) {
            throw new InvalidRequestException('Alternate identifier IRI is required for this operation');
        }

        $this->client->delete($request->getIri());
    }
}
