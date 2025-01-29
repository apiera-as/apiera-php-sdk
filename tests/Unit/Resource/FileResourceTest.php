<?php

declare(strict_types=1);

namespace Tests\Unit\Resource;

use Apiera\Sdk\Client;
use Apiera\Sdk\DataMapper\FileDataMapper;
use Apiera\Sdk\DTO\QueryParameters;
use Apiera\Sdk\DTO\Request\File\FileRequest;
use Apiera\Sdk\DTO\Response\File\FileCollectionResponse;
use Apiera\Sdk\DTO\Response\File\FileResponse;
use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Exception\InvalidRequestException;
use Apiera\Sdk\Exception\NotSupportedOperationException;
use Apiera\Sdk\Resource\FileResource;
use DateTimeImmutable;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Uid\Uuid;

final class FileResourceTest extends TestCase
{
    private Client|MockObject $clientMock;
    private FileDataMapper|MockObject $mapperMock;
    private FileResource $resource;
    private FileRequest $request;
    private FileResponse $response;
    private FileCollectionResponse $collectionResponse;

    /** @var array<string, mixed> */
    private array $mockResponseData;

    /** @var array<string, mixed> */
    private array $mockCollectionData;

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testFindReturnsCollection(): void
    {
        $responseMock = $this->createMock(ResponseInterface::class);

        $this->clientMock->expects($this->once())
            ->method('get')
            ->with('/api/v1/files')
            ->willReturn($responseMock);

        $this->clientMock->expects($this->once())
            ->method('decodeResponse')
            ->with($responseMock)
            ->willReturn($this->mockCollectionData);

        $this->mapperMock->expects($this->once())
            ->method('fromCollectionResponse')
            ->with($this->mockCollectionData)
            ->willReturn($this->collectionResponse);

        $result = $this->resource->find($this->request);
        $this->assertSame($this->collectionResponse, $result);
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testFindOneByReturnsFirstResult(): void
    {
        $params = new QueryParameters(filters: ['name' => 'test.jpg']);

        $this->clientMock->expects($this->once())
            ->method('get')
            ->with('/api/v1/files')
            ->willReturn($this->createMock(ResponseInterface::class));

        $this->clientMock->expects($this->once())
            ->method('decodeResponse')
            ->willReturn($this->mockCollectionData);

        $this->mapperMock->expects($this->once())
            ->method('fromCollectionResponse')
            ->willReturn($this->collectionResponse);

        $result = $this->resource->findOneBy($this->request, $params);
        $this->assertSame($this->response, $result);
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testFindOneByThrowsExceptionWhenEmpty(): void
    {
        $emptyCollectionData = [
            '@context' => '/api/contexts/File',
            '@id' => '/api/v1/files',
            '@type' => 'Collection',
            'member' => [],
            'totalItems' => 0,
        ];

        $emptyCollection = new FileCollectionResponse(
            context: '/api/v1/contexts/File',
            id: '/api/v1/files',
            type: LdType::Collection,
            members: [],
            totalItems: 0
        );

        $this->clientMock->method('get')
            ->willReturn($this->createMock(ResponseInterface::class));

        $this->clientMock->method('decodeResponse')
            ->willReturn($emptyCollectionData);

        $this->mapperMock->method('fromCollectionResponse')
            ->willReturn($emptyCollection);

        $this->expectException(InvalidRequestException::class);
        $this->resource->findOneBy($this->request, new QueryParameters());
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     */
    public function testGetRequiresIri(): void
    {
        $this->expectException(InvalidRequestException::class);
        $this->resource->get(new FileRequest(url: 'https://example.com/test.jpg'));
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testGetReturnsFile(): void
    {
        $request = new FileRequest(
            url: 'https://example.com/test.jpg',
            name: 'test.jpg',
            iri: '/api/v1/files/456'
        );

        $responseMock = $this->createMock(ResponseInterface::class);

        $this->clientMock->expects($this->once())
            ->method('get')
            ->with('/api/v1/files/456')
            ->willReturn($responseMock);

        $this->clientMock->expects($this->once())
            ->method('decodeResponse')
            ->with($responseMock)
            ->willReturn($this->mockResponseData);

        $this->mapperMock->expects($this->once())
            ->method('fromResponse')
            ->with($this->mockResponseData)
            ->willReturn($this->response);

        $result = $this->resource->get($request);
        $this->assertSame($this->response, $result);
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testCreateFile(): void
    {
        $requestData = [
            'url' => 'https://example.com/test.jpg',
            'name' => 'test.jpg',
        ];

        $responseMock = $this->createMock(ResponseInterface::class);

        $this->mapperMock->expects($this->once())
            ->method('toRequestData')
            ->with($this->request)
            ->willReturn($requestData);

        $this->clientMock->expects($this->once())
            ->method('post')
            ->with('/api/v1/files', $requestData)
            ->willReturn($responseMock);

        $this->clientMock->expects($this->once())
            ->method('decodeResponse')
            ->with($responseMock)
            ->willReturn($this->mockResponseData);

        $this->mapperMock->expects($this->once())
            ->method('fromResponse')
            ->with($this->mockResponseData)
            ->willReturn($this->response);

        $result = $this->resource->create($this->request);
        $this->assertSame($this->response, $result);
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     */
    public function testUpdateThrowsNotSupportedException(): void
    {
        $this->expectException(NotSupportedOperationException::class);
        $this->resource->update($this->request);
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     */
    public function testDeleteRequiresIri(): void
    {
        $this->expectException(InvalidRequestException::class);
        $this->resource->delete(new FileRequest(url: 'https://example.com/test.jpg'));
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testDeleteFile(): void
    {
        $request = new FileRequest(
            url: 'https://example.com/test.jpg',
            name: 'test.jpg',
            iri: '/api/v1/files/456'
        );

        $responseMock = $this->createMock(ResponseInterface::class);

        $this->clientMock->expects($this->once())
            ->method('delete')
            ->with('/api/v1/files/456')
            ->willReturn($responseMock);

        $this->resource->delete($request);
    }

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    protected function setUp(): void
    {
        $this->clientMock = $this->createMock(Client::class);
        $this->mapperMock = $this->createMock(FileDataMapper::class);
        $this->resource = new FileResource($this->clientMock, $this->mapperMock);

        // Setup realistic test data
        $this->request = new FileRequest(
            url: 'https://example.com/test.jpg',
            name: 'test.jpg'
        );

        $uuid = Uuid::fromString('f47ac10b-58cc-4372-a567-0e02b2c3d479');
        $createdAt = new DateTimeImmutable('2024-01-25T12:00:00+00:00');
        $updatedAt = new DateTimeImmutable('2024-01-25T12:00:00+00:00');

        $this->response = new FileResponse(
            id: '/api/v1/files/456',
            type: LdType::File,
            uuid: $uuid,
            createdAt: $createdAt,
            updatedAt: $updatedAt,
            url: 'https://example.com/test.jpg',
            name: 'test.jpg'
        );

        $this->mockResponseData = [
            '@id' => '/api/v1/files/456',
            '@type' => 'File',
            'uuid' => 'f47ac10b-58cc-4372-a567-0e02b2c3d479',
            'url' => 'https://example.com/test.jpg',
            'name' => 'test.jpg',
            'createdAt' => '2024-01-25T12:00:00+00:00',
            'updatedAt' => '2024-01-25T12:00:00+00:00',
        ];

        $this->mockCollectionData = [
            '@context' => '/api/v1/contexts/File',
            '@id' => '/api/v1/files',
            '@type' => 'Collection',
            'member' => [$this->mockResponseData],
            'totalItems' => 1,
            'view' => '/api/v1/files?page=1',
            'firstPage' => '/api/v1/files?page=1',
            'lastPage' => '/api/v1/files?page=1',
        ];

        $this->collectionResponse = new FileCollectionResponse(
            context: '/api/v1/contexts/File',
            id: '/api/files',
            type: LdType::Collection,
            members: [$this->response],
            totalItems: 1,
            view: '/api/v1/files?page=1',
            firstPage: '/api/v1/files?page=1',
            lastPage: '/api/v1/files?page=1'
        );
    }
}
