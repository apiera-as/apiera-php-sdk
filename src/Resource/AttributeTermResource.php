<?php

declare(strict_types=1);

namespace Apiera\Sdk\Resource;

use Apiera\Sdk\DTO\QueryParameters;
use Apiera\Sdk\DTO\Request\AttributeTerm\AttributeTermRequest;
use Apiera\Sdk\DTO\Response\AttributeTerm\AttributeTermCollectionResponse;
use Apiera\Sdk\DTO\Response\AttributeTerm\AttributeTermResponse;
use Apiera\Sdk\Exception\InvalidRequestException;
use Apiera\Sdk\Interface\ClientInterface;
use Apiera\Sdk\Interface\DataMapperInterface;
use Apiera\Sdk\Interface\DTO\RequestInterface;
use Apiera\Sdk\Interface\RequestResourceInterface;

/**
 * @author Marie Rinden <marie@shoppingnorge.no>
 * @since 0.3.0
 */
final readonly class AttributeTermResource implements RequestResourceInterface
{
    private const string ENDPOINT = '/terms';

    public function __construct(
        private ClientInterface $client,
        private DataMapperInterface $mapper,
    ) {
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws InvalidRequestException
     */
    public function find(RequestInterface $request, ?QueryParameters $params = null): AttributeTermCollectionResponse
    {
        if (!$request instanceof AttributeTermRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', AttributeTermRequest::class)
            );
        }

        if (!$request->getAttribute()) {
            throw new InvalidRequestException('Attribute IRI is required for this operation');
        }

        /** @var AttributeTermCollectionResponse $collectionResponse */
        $collectionResponse = $this->mapper->fromCollectionResponse($this->client->decodeResponse(
            $this->client->get($request->getAttribute() . self::ENDPOINT, $params)
        ));

        return $collectionResponse;
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws InvalidRequestException
     */
    public function findOneBy(RequestInterface $request, QueryParameters $params): AttributeTermResponse
    {
        if (!$request instanceof AttributeTermRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', AttributeTermRequest::class)
            );
        }

        $collection = $this->find($request, $params);

        if ($collection->getTotalItems() < 1) {
            throw new InvalidRequestException('No attribute term found matching the given criteria');
        }

        return $collection->getMembers()[0];
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws InvalidRequestException
     */
    public function get(RequestInterface $request): AttributeTermResponse
    {
        if (!$request instanceof AttributeTermRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', AttributeTermRequest::class)
            );
        }

        if (!$request->getIri()) {
            throw new InvalidRequestException('Attribute term IRI is required for this operation');
        }

        /** @var AttributeTermResponse $response */
        $response = $this->mapper->fromResponse($this->client->decodeResponse(
            $this->client->get($request->getIri())
        ));

        return $response;
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws InvalidRequestException
     */
    public function create(RequestInterface $request): AttributeTermResponse
    {
        if (!$request instanceof AttributeTermRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', AttributeTermRequest::class)
            );
        }

        if (!$request->getAttribute()) {
            throw new InvalidRequestException('Attribute IRI is required for this operation');
        }

        $requestData = $this->mapper->toRequestData($request);

        /** @var AttributeTermResponse $response */
        $response = $this->mapper->fromResponse($this->client->decodeResponse(
            $this->client->post($request->getAttribute() . self::ENDPOINT, $requestData)
        ));

        return $response;
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws InvalidRequestException
     */
    public function update(RequestInterface $request): AttributeTermResponse
    {
        if (!$request instanceof AttributeTermRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', AttributeTermRequest::class)
            );
        }

        if (!$request->getIri()) {
            throw new InvalidRequestException('Attribute term IRI is required for this operation');
        }

        $requestData = $this->mapper->toRequestData($request);

        /** @var AttributeTermResponse $response */
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
        if (!$request instanceof AttributeTermRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', AttributeTermRequest::class)
            );
        }

        if (!$request->getIri()) {
            throw new InvalidRequestException('Attribute Term IRI is required for this operation');
        }

        $this->client->delete($request->getIri());
    }
}
