<?php

namespace Apiera\Sdk\Resource;

use Apiera\Sdk\Client;
use Apiera\Sdk\DataMapper\AttributeDataMapper;
use Apiera\Sdk\DTO\QueryParameters;
use Apiera\Sdk\DTO\Request\Attribute\AttributeRequest;
use Apiera\Sdk\DTO\Response\Attribute\AttributeCollectionResponse;
use Apiera\Sdk\DTO\Response\Attribute\AttributeResponse;
use Apiera\Sdk\Exception\InvalidRequestException;
use Apiera\Sdk\Interface\ClientExceptionInterface;
use Apiera\Sdk\Interface\ClientInterface;
use Apiera\Sdk\Interface\DataMapperInterface;
use Apiera\Sdk\Interface\DTO\JsonLDInterface;
use Apiera\Sdk\Interface\DTO\RequestInterface;
use Apiera\Sdk\Interface\DTO\ResponseInterface;
use Apiera\Sdk\Interface\RequestResourceInterface;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @package Apiera\Sdk\Resource
 * @since 0.2.0
 */
readonly final class AttributeResource implements RequestResourceInterface
{
    private const string ENDPOINT = '/attributes';

    /**
     * @param Client $client
     * @param AttributeDataMapper $mapper
     */
    public function __construct(
        private ClientInterface $client,
        private DataMapperInterface $mapper,
    ) {
    }

    /**
     * @param AttributeRequest $request
     * @param QueryParameters|null $params
     * @return AttributeCollectionResponse
     * @throws ClientExceptionInterface
     * @throws InvalidRequestException
     */
    public function find(RequestInterface $request, ?QueryParameters $params = null): JsonLDInterface
    {
        if (!$request->getStore()) {
            throw new InvalidRequestException('Store IRI is required for this operation');
        }

        return $this->mapper->fromCollectionResponse($this->client->decodeResponse(
            $this->client->get($request->getStore() . self::ENDPOINT, $params)
        ));
    }

    /**
     * @param AttributeRequest $request
     * @param QueryParameters|null $params
     * @return AttributeResponse
     * @throws ClientExceptionInterface
     * @throws InvalidRequestException
     */
    public function findOneBy(RequestInterface $request, ?QueryParameters $params = null): ResponseInterface
    {
        $collection = $this->find($request, $params);

        if ($collection->getTotalItems() < 1) {
            throw new InvalidRequestException('No attribute found matching the given criteria');
        }

        return $collection->getMembers()[0];
    }

    /**
     * @param AttributeRequest $request
     * @return AttributeResponse
     * @throws ClientExceptionInterface
     * @throws InvalidRequestException
     */
    public function get(RequestInterface $request): ResponseInterface
    {
        if (!$request->getIri()) {
            throw new InvalidRequestException('Attribute IRI is required for this operation');
        }

        return $this->mapper->fromResponse($this->client->decodeResponse(
            $this->client->get($request->getIri())
        ));
    }

    /**
     * @param AttributeRequest $request
     * @return AttributeResponse
     * @throws ClientExceptionInterface
     * @throws InvalidRequestException
     */
    public function create(RequestInterface $request): ResponseInterface
    {
        if (!$request->getStore()) {
            throw new InvalidRequestException('Store IRI is required for this operation');
        }

        $requestData = $this->mapper->toRequestData($request);

        return $this->mapper->fromResponse($this->client->decodeResponse(
            $this->client->post($request->getStore() . self::ENDPOINT, $requestData)
        ));
    }

    /**
     * @param AttributeRequest $request
     * @return AttributeResponse
     * @throws ClientExceptionInterface
     * @throws InvalidRequestException
     */
    public function update(RequestInterface $request): ResponseInterface
    {
        if (!$request->getIri()) {
            throw new InvalidRequestException('Attribute IRI is required for this operation');
        }

        $requestData = $this->mapper->toRequestData($request);

        return $this->mapper->fromResponse($this->client->decodeResponse(
            $this->client->patch($request->getIri(), $requestData)
        ));
    }

    /**
     * @param AttributeRequest $request
     * @return void
     * @throws ClientExceptionInterface
     * @throws InvalidRequestException
     */
    public function delete(RequestInterface $request): void
    {
        if (!$request->getIri()) {
            throw new InvalidRequestException('Attribute IRI is required for this operation');
        }

        $this->client->delete($request->getIri());
    }
}
