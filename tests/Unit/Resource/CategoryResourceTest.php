<?php

declare(strict_types=1);

namespace Tests\Unit\Resource;

use Apiera\Sdk\Client;
use Apiera\Sdk\DataMapper\CategoryDataMapper;
use Apiera\Sdk\DataMapper\ReflectionAttributeDataMapper;
use Apiera\Sdk\DTO\QueryParameters;
use Apiera\Sdk\DTO\Request\Category\CategoryRequest;
use Apiera\Sdk\DTO\Response\Category\CategoryCollectionResponse;
use Apiera\Sdk\DTO\Response\Category\CategoryResponse;
use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Exception\InvalidRequestException;
use Apiera\Sdk\Resource\CategoryResource;
use DateTimeImmutable;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Uid\Uuid;

final class CategoryResourceTest extends TestCase
{
    private Client|MockObject $clientMock;
    private ReflectionAttributeDataMapper|MockObject $mapperMock;
    private CategoryResource $resource;
    private CategoryRequest $request;
    private CategoryResponse $response;
    private CategoryCollectionResponse $collectionResponse;

    /** @var array<string, mixed> */
    private array $mockResponseData;

    /** @var array<string, mixed> */
    private array $mockCollectionData;

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     */
    public function testFindRequiresStoreIri(): void
    {
        $this->expectException(InvalidRequestException::class);
        $this->resource->find(new CategoryRequest(name: 'Electronics'));
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testFindReturnsCollection(): void
    {
        $responseMock = $this->createMock(ResponseInterface::class);

        $this->clientMock->expects($this->once())
            ->method('get')
            ->with('/api/stores/123/categories')
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
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testFindOneByReturnsFirstResult(): void
    {
        $params = new QueryParameters(filters: ['name' => 'Electronics']);

        $this->clientMock->expects($this->once())
            ->method('get')
            ->with('/api/stores/123/categories')
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
            '@context' => '/api/contexts/Category',
            '@id' => '/api/categories',
            '@type' => 'Collection',
            'member' => [],
            'totalItems' => 0,
        ];

        $emptyCollection = new CategoryCollectionResponse(
            context: '/api/contexts/Category',
            id: '/api/categories',
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
        $this->resource->get(new CategoryRequest(name: 'Electronics'));
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testGetReturnsCategory(): void
    {
        $request = new CategoryRequest(
            name: 'Electronics',
            iri: '/api/categories/456'
        );

        $responseMock = $this->createMock(ResponseInterface::class);

        $this->clientMock->expects($this->once())
            ->method('get')
            ->with('/api/categories/456')
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
     */
    public function testCreateRequiresStoreIri(): void
    {
        $this->expectException(InvalidRequestException::class);
        $this->resource->create(new CategoryRequest(name: 'Electronics'));
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testCreateCategory(): void
    {
        $requestData = [
            'name' => 'Electronics',
            'description' => 'Electronic devices and accessories',
        ];

        $responseMock = $this->createMock(ResponseInterface::class);

        $this->mapperMock->expects($this->once())
            ->method('toRequestData')
            ->with($this->request)
            ->willReturn($requestData);

        $this->clientMock->expects($this->once())
            ->method('post')
            ->with('/api/stores/123/categories', $requestData)
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
    public function testUpdateRequiresIri(): void
    {
        $this->expectException(InvalidRequestException::class);
        $this->resource->update(new CategoryRequest(name: 'Electronics'));
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testUpdateCategory(): void
    {
        $request = new CategoryRequest(
            name: 'Electronics Updated',
            iri: '/api/categories/456'
        );

        $requestData = ['name' => 'Electronics Updated'];
        $updatedResponseData = array_merge($this->mockResponseData, ['name' => 'Electronics Updated']);
        $responseMock = $this->createMock(ResponseInterface::class);

        $this->mapperMock->expects($this->once())
            ->method('toRequestData')
            ->with($request)
            ->willReturn($requestData);

        $this->clientMock->expects($this->once())
            ->method('patch')
            ->with('/api/categories/456', $requestData)
            ->willReturn($responseMock);

        $this->clientMock->expects($this->once())
            ->method('decodeResponse')
            ->with($responseMock)
            ->willReturn($updatedResponseData);

        $updatedResponse = new CategoryResponse(
            ldId: '/api/categories/456',
            ldType: LdType::Category,
            uuid: Uuid::fromString('f47ac10b-58cc-4372-a567-0e02b2c3d479'),
            createdAt: new DateTimeImmutable('2024-01-25T12:00:00+00:00'),
            updatedAt: new DateTimeImmutable('2024-01-25T12:00:00+00:00'),
            name: 'Electronics Updated',
            store: '/api/stores/123',
            description: 'Electronic devices and accessories'
        );

        $this->mapperMock->expects($this->once())
            ->method('fromResponse')
            ->with($updatedResponseData)
            ->willReturn($updatedResponse);

        $result = $this->resource->update($request);
        $this->assertSame($updatedResponse, $result);
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     */
    public function testDeleteRequiresIri(): void
    {
        $this->expectException(InvalidRequestException::class);
        $this->resource->delete(new CategoryRequest(name: 'Electronics'));
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testDeleteCategory(): void
    {
        $request = new CategoryRequest(
            name: 'Electronics',
            iri: '/api/categories/456'
        );

        $responseMock = $this->createMock(ResponseInterface::class);

        $this->clientMock->expects($this->once())
            ->method('delete')
            ->with('/api/categories/456')
            ->willReturn($responseMock);

        $this->resource->delete($request);
    }

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    protected function setUp(): void
    {
        $this->clientMock = $this->createMock(Client::class);
        $this->mapperMock = $this->createMock(ReflectionAttributeDataMapper::class);
        $this->resource = new CategoryResource($this->clientMock, $this->mapperMock);

        // Setup realistic test data
        $this->request = new CategoryRequest(
            name: 'Electronics',
            store: '/api/stores/123',
            description: 'Electronic devices and accessories'
        );

        $uuid = Uuid::fromString('f47ac10b-58cc-4372-a567-0e02b2c3d479');
        $createdAt = new DateTimeImmutable('2024-01-25T12:00:00+00:00');
        $updatedAt = new DateTimeImmutable('2024-01-25T12:00:00+00:00');

        $this->response = new CategoryResponse(
            ldId: '/api/categories/456',
            ldType: LdType::Category,
            uuid: $uuid,
            createdAt: $createdAt,
            updatedAt: $updatedAt,
            name: 'Electronics',
            store: '/api/stores/123',
            description: 'Electronic devices and accessories'
        );

        $this->mockResponseData = [
            '@id' => '/api/categories/456',
            '@type' => 'Category',
            'uuid' => 'f47ac10b-58cc-4372-a567-0e02b2c3d479',
            'name' => 'Electronics',
            'store' => '/api/stores/123',
            'description' => 'Electronic devices and accessories',
            'createdAt' => '2024-01-25T12:00:00+00:00',
            'updatedAt' => '2024-01-25T12:00:00+00:00',
        ];

        $this->mockCollectionData = [
            '@context' => '/api/contexts/Category',
            '@id' => '/api/categories',
            '@type' => 'Collection',
            'member' => [$this->mockResponseData],
            'totalItems' => 1,
            'view' => '/api/categories?page=1',
            'firstPage' => '/api/categories?page=1',
            'lastPage' => '/api/categories?page=1',
        ];

        $this->collectionResponse = new CategoryCollectionResponse(
            context: '/api/contexts/Category',
            id: '/api/categories',
            type: LdType::Collection,
            members: [$this->response],
            totalItems: 1,
            view: '/api/categories?page=1',
            firstPage: '/api/categories?page=1',
            lastPage: '/api/categories?page=1'
        );
    }
}
