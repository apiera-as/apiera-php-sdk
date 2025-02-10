<?php

declare(strict_types=1);

namespace Apiera\Sdk\Resource;

use Apiera\Sdk\DTO\QueryParameters;
use Apiera\Sdk\DTO\Request\Organization\OrganizationRequest;
use Apiera\Sdk\DTO\Response\Organization\OrganizationCollectionResponse;
use Apiera\Sdk\DTO\Response\Organization\OrganizationResponse;
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
final readonly class OrganizationResource implements RequestResourceInterface
{
    private const string ENDPOINT = '/api/v1/organizations';

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
    public function find(RequestInterface $request, ?QueryParameters $params = null): OrganizationCollectionResponse
    {
        if (!$request instanceof OrganizationRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', OrganizationRequest::class)
            );
        }

        /** @var OrganizationCollectionResponse $collectionResponse */
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
    public function findOneBy(RequestInterface $request, QueryParameters $params): OrganizationResponse
    {
        if (!$request instanceof OrganizationRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', OrganizationRequest::class)
            );
        }

        $collection = $this->find($request, $params);

        if ($collection->getLdTotalItems() < 1) {
            throw new InvalidRequestException('No organization found matching the given criteria');
        }

        return $collection->getLdMembers()[0];
    }

    /**
     * @throws InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function get(RequestInterface $request): Organizationresponse
    {
        if (!$request instanceof OrganizationRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', OrganizationRequest::class)
            );
        }

        if (!$request->getIri()) {
            throw new InvalidRequestException('Organization IRI is required for this operation');
        }

        /** @var OrganizationResponse $response */
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
    public function create(RequestInterface $request): OrganizationResponse
    {
        if (!$request instanceof OrganizationRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', OrganizationRequest::class)
            );
        }

        $requestData = $this->mapper->toRequestData($request);

        /** @var OrganizationResponse $response */
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
    public function update(RequestInterface $request): ResponseInterface
    {
        if (!$request instanceof OrganizationRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', OrganizationRequest::class)
            );
        }

        if (!$request->getIri()) {
            throw new InvalidRequestException('Organization IRI is required for this operation');
        }

        $requestData = $this->mapper->toRequestData($request);

        /** @var OrganizationResponse $response */
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
        if (!$request instanceof OrganizationRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', OrganizationRequest::class)
            );
        }

        if (!$request->getIri()) {
            throw new InvalidRequestException('Organization IRI is required for this operation');
        }

        $this->client->delete($request->getIri());
    }
}
