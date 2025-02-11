<?php

declare(strict_types=1);

namespace Apiera\Sdk\Resource;

use Apiera\Sdk\DTO\QueryParameters;
use Apiera\Sdk\DTO\Request\Tag\TagRequest;
use Apiera\Sdk\DTO\Response\Tag\TagCollectionResponse;
use Apiera\Sdk\DTO\Response\Tag\TagResponse;
use Apiera\Sdk\Exception\InvalidRequestException;
use Apiera\Sdk\Interface\ClientInterface;
use Apiera\Sdk\Interface\DataMapperInterface;
use Apiera\Sdk\Interface\DTO\RequestInterface;
use Apiera\Sdk\Interface\RequestResourceInterface;

/**
 * @author Marie Rinden <marie@shoppingnoge.no>
 * @since 1.0.0
 */
final readonly class TagResource implements RequestResourceInterface
{
    private const string ENDPOINT = '/tags';

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
    public function find(RequestInterface $request, ?QueryParameters $params = null): TagCollectionResponse
    {
        if (!$request instanceof TagRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', TagRequest::class)
            );
        }

        if (!$request->getStore()) {
            throw new InvalidRequestException('Store IRI is required for this operation');
        }

        /** @var TagCollectionResponse $collectionResponse */
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
    public function findOneBy(RequestInterface $request, QueryParameters $params): TagResponse
    {
        if (!$request instanceof TagRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', TagRequest::class)
            );
        }

        $collection = $this->find($request, $params);

        if ($collection->getLdTotalItems() < 1) {
            throw new InvalidRequestException('No tag found matching the given criteria');
        }

        return $collection->getLdMembers()[0];
    }

    /**
     * @throws InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function get(RequestInterface $request): TagResponse
    {
        if (!$request instanceof TagRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', TagRequest::class)
            );
        }

        if (!$request->getIri()) {
            throw new InvalidRequestException('Tag IRI is required for this operation');
        }

        /** @var TagResponse $response */
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
    public function create(RequestInterface $request): TagResponse
    {
        if (!$request instanceof TagRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', TagRequest::class)
            );
        }

        if (!$request->getStore()) {
            throw new InvalidRequestException('Store IRI is required for this operation');
        }

        $requestData = $this->mapper->toRequestData($request);

        /** @var TagResponse $response */
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
    public function update(RequestInterface $request): TagResponse
    {
        if (!$request instanceof TagRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', TagRequest::class)
            );
        }

        if (!$request->getIri()) {
            throw new InvalidRequestException('Tag IRI is required for this operation');
        }

        $requestData = $this->mapper->toRequestData($request);

        /** @var TagResponse $response */
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
        if (!$request instanceof TagRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', TagRequest::class)
            );
        }

        if (!$request->getIri()) {
            throw new InvalidRequestException('Tag IRI is required for this operation');
        }

        $this->client->delete($request->getIri());
    }
}
