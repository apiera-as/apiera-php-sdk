<?php

declare(strict_types=1);

namespace Apiera\Sdk\Resource;

use Apiera\Sdk\Client;
use Apiera\Sdk\DataMapper\AlternateIdentifierDataMapper;
use Apiera\Sdk\DTO\QueryParameters;
use Apiera\Sdk\DTO\Request\AlternateIdentifier\AlternateIdentifierRequest;
use Apiera\Sdk\DTO\Response\AlternateIdentifier\AlternateIdentifierCollectionResponse;
use Apiera\Sdk\DTO\Response\AlternateIdentifier\AlternateIdentifierResponse;
use Apiera\Sdk\Exception\InvalidRequestException;
use Apiera\Sdk\Interface\ClientExceptionInterface;
use Apiera\Sdk\Interface\ClientInterface;
use Apiera\Sdk\Interface\DataMapperInterface;
use Apiera\Sdk\Interface\DTO\JsonLDInterface;
use Apiera\Sdk\Interface\DTO\RequestInterface;
use Apiera\Sdk\Interface\DTO\ResponseInterface;
use Apiera\Sdk\Interface\RequestResourceInterface;

/**
 * @author Marie Rinden <marie@shoppingnorge.no>
 * @package Apiera\Sdk\Resource
 * @since 0.2.0
 */
final readonly class AlternateIdentifierResource implements RequestResourceInterface
{
    private const string ENDPOINT = '/api/v1/alternate_identifiers';

    /**
     * @param Client $client
     * @param AlternateIdentifierDataMapper $mapper
     */
    public function __construct(
        private ClientInterface $client,
        private DataMapperInterface $mapper,
    ) {
    }

    /**
     * @param AlternateIdentifierRequest $request
     * @param QueryParameters|null $params
     * @return AlternateIdentifierCollectionResponse
     * @throws ClientExceptionInterface
     */
    public function find(RequestInterface $request, ?QueryParameters $params = null): JsonLDInterface
    {
        return $this->mapper->fromCollectionResponse($this->client->decodeResponse(
            $this->client->get(self::ENDPOINT, $params)
        ));
    }

    /**
     * @param AlternateIdentifierRequest $request
     * @param QueryParameters $params
     * @return AlternateIdentifierResponse
     * @throws ClientExceptionInterface
     * @throws InvalidRequestException
     */
    public function findOneBy(RequestInterface $request, QueryParameters $params): ResponseInterface
    {
        $collection = $this->find($request, $params);

        if ($collection->getTotalItems() < 1) {
            throw new InvalidRequestException('No alternate identifier found matching the given criteria');
        }

        return $collection->getMembers()[0];
    }

    /**
     * @param AlternateIdentifierRequest $request
     * @return AlternateIdentifierResponse
     * @throws ClientExceptionInterface
     * @throws InvalidRequestException
     */
    public function get(RequestInterface $request): ResponseInterface
    {
        if (!$request->getIri()) {
            throw new InvalidRequestException('Alternate identifier IRI is required for this operation');
        }

        return $this->mapper->fromResponse($this->client->decodeResponse(
            $this->client->get($request->getIri())
        ));
    }

    /**
     * @param AlternateIdentifierRequest $request
     * @return AlternateIdentifierResponse
     * @throws ClientExceptionInterface
     */
    public function create(RequestInterface $request): ResponseInterface
    {
        $requestData = $this->mapper->toRequestData($request);

        return $this->mapper->fromResponse($this->client->decodeResponse(
            $this->client->post(self::ENDPOINT, $requestData)
        ));
    }

    /**
     * @param AlternateIdentifierRequest $request
     * @return AlternateIdentifierResponse
     * @throws ClientExceptionInterface
     * @throws InvalidRequestException
     */
    public function update(RequestInterface $request): ResponseInterface
    {
        if (!$request->getIri()) {
            throw new InvalidRequestException('Alternate identifier IRI is required for this operation');
        }

        $requestData = $this->mapper->toRequestData($request);

        return $this->mapper->fromResponse($this->client->decodeResponse(
            $this->client->patch($request->getIri(), $requestData)
        ));
    }

    /**
     * @param AlternateIdentifierRequest $request
     * @return void
     * @throws ClientExceptionInterface
     * @throws InvalidRequestException
     */
    public function delete(RequestInterface $request): void
    {
        if (!$request->getIri()) {
            throw new InvalidRequestException('Alternate identifier IRI is required for this operation');
        }

        $this->client->delete($request->getIri());
    }
}
