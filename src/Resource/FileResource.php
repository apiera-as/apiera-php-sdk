<?php

declare(strict_types=1);

namespace Apiera\Sdk\Resource;

use Apiera\Sdk\DTO\QueryParameters;
use Apiera\Sdk\DTO\Request\File\FileRequest;
use Apiera\Sdk\DTO\Response\File\FileCollectionResponse;
use Apiera\Sdk\DTO\Response\File\FileResponse;
use Apiera\Sdk\Exception\InvalidRequestException;
use Apiera\Sdk\Exception\NotSupportedOperationException;
use Apiera\Sdk\Interface\ClientInterface;
use Apiera\Sdk\Interface\DataMapperInterface;
use Apiera\Sdk\Interface\DTO\RequestInterface;
use Apiera\Sdk\Interface\RequestResourceInterface;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 0.3.0
 */
final readonly class FileResource implements RequestResourceInterface
{
    private const string ENDPOINT = '/api/v1/files';

    public function __construct(
        private ClientInterface $client,
        private DataMapperInterface $mapper,
    ) {
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws InvalidRequestException
     */
    public function find(RequestInterface $request, ?QueryParameters $params = null): FileCollectionResponse
    {
        if (!$request instanceof FileRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', FileRequest::class)
            );
        }

        /** @var FileCollectionResponse $collectionResponse */
        $collectionResponse = $this->mapper->fromCollectionResponse($this->client->decodeResponse(
            $this->client->get(self::ENDPOINT, $params)
        ));

        return $collectionResponse;
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws InvalidRequestException
     */
    public function findOneBy(RequestInterface $request, QueryParameters $params): FileResponse
    {
        if (!$request instanceof FileRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', FileRequest::class)
            );
        }

        $collection = $this->find($request, $params);

        if ($collection->getTotalItems() < 1) {
            throw new InvalidRequestException('No file found matching the given criteria');
        }

        return $collection->getMembers()[0];
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws InvalidRequestException
     */
    public function get(RequestInterface $request): FileResponse
    {
        if (!$request instanceof FileRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', FileRequest::class)
            );
        }

        if (!$request->getIri()) {
            throw new InvalidRequestException('File IRI is required for this operation');
        }

        /** @var FileResponse $response */
        $response = $this->mapper->fromResponse($this->client->decodeResponse(
            $this->client->get($request->getIri())
        ));

        return $response;
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws InvalidRequestException
     */
    public function create(RequestInterface $request): FileResponse
    {
        if (!$request instanceof FileRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', FileRequest::class)
            );
        }

        $requestData = $this->mapper->toRequestData($request);

        /** @var FileResponse $response */
        $response = $this->mapper->fromResponse($this->client->decodeResponse(
            $this->client->post(self::ENDPOINT, $requestData)
        ));

        return $response;
    }

    /**
     * @throws NotSupportedOperationException
     */
    public function update(RequestInterface $request): FileResponse
    {
        throw new NotSupportedOperationException('The file resource does not support update operations');
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws InvalidRequestException
     */
    public function delete(RequestInterface $request): void
    {
        if (!$request instanceof FileRequest) {
            throw new InvalidRequestException(
                sprintf('Request must be an instance of %s', FileRequest::class)
            );
        }

        if (!$request->getIri()) {
            throw new InvalidRequestException('File IRI is required for this operation');
        }

        $this->client->delete($request->getIri());
    }
}
