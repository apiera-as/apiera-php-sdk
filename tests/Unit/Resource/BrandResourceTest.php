<?php

declare(strict_types=1);

namespace Tests\Unit\Resource;

use Apiera\Sdk\Client;
use Apiera\Sdk\DataMapper\ReflectionAttributeDataMapper;
use Apiera\Sdk\DTO\QueryParameters;
use Apiera\Sdk\DTO\Request\Brand\BrandRequest;
use Apiera\Sdk\DTO\Response\Brand\BrandCollectionResponse;
use Apiera\Sdk\DTO\Response\Brand\BrandResponse;
use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Exception\InvalidRequestException;
use Apiera\Sdk\Resource\BrandResource;
use DateTimeImmutable;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Uid\Uuid;

final class BrandResourceTest extends TestCase
{
    private MockObject $clientMock;
    private MockObject $mapperMock;
    private BrandResource $resource;
    private BrandRequest $request;
    private BrandResponse $response;
    private BrandCollectionResponse $collectionResponse;

    /** @var array<string, mixed> */
    private array $mockResponseData;

    /** @var array<string, mixed> */
    private array $mockCollectionData;

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function testFindRequiresStoreIri(): void
    {
        $this->expectException(InvalidRequestException::class);
        $this->resource->find(new BrandRequest(name: 'Test Brand'));
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testFindReturnsCollection(): void
    {
        $responseMock = $this->createMock(ResponseInterface::class);

        $this->clientMock->expects($this->once())
            ->method('get')
            ->with('/api/v1/stores/123/brands')
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
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testFindOneByReturnsFirstResult(): void
    {
        $params = new QueryParameters(filters: ['name' => 'Test Brand']);

        $this->clientMock->expects($this->once())
            ->method('get')
            ->with('/api/v1/stores/123/brands')
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
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testFindOneByThrowsExceptionWhenEmpty(): void
    {
        $emptyCollectionData = [
            '@context' => '/api/contexts/Brand',
            '@id' => '/api/v1/stores/123/brands',
            '@type' => 'Collection',
            'member' => [],
            'totalItems' => 0,
        ];

        $emptyCollection = new BrandCollectionResponse(
            context: '/api/contexts/Brand',
            id: '/api/v1/stores/123/brands',
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
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function testGetRequiresIri(): void
    {
        $this->expectException(InvalidRequestException::class);
        $this->resource->get(new BrandRequest(name: 'Test Brand'));
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testGetReturnsBrand(): void
    {
        $request = new BrandRequest(
            name: 'Test Brand',
            iri: '/api/v1/stores/123/brands/456'
        );

        $responseMock = $this->createMock(ResponseInterface::class);

        $this->clientMock->expects($this->once())
            ->method('get')
            ->with('/api/v1/stores/123/brands/456')
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
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function testCreateRequiresStoreIri(): void
    {
        $this->expectException(InvalidRequestException::class);
        $this->resource->create(new BrandRequest(name: 'Test Brand'));
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testCreateBrand(): void
    {
        $requestData = [
            'name' => 'Test Brand',
            'description' => 'Test Description',
            'image' => '/api/v1/files/789',
        ];

        $responseMock = $this->createMock(ResponseInterface::class);

        $this->mapperMock->expects($this->once())
            ->method('toRequestData')
            ->with($this->request)
            ->willReturn($requestData);

        $this->clientMock->expects($this->once())
            ->method('post')
            ->with('/api/v1/stores/123/brands', $requestData)
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
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function testUpdateRequiresIri(): void
    {
        $this->expectException(InvalidRequestException::class);
        $this->resource->update(new BrandRequest(name: 'Test Brand'));
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testUpdateBrand(): void
    {
        $request = new BrandRequest(
            name: 'Updated Brand',
            iri: '/api/v1/stores/123/brands/456'
        );

        $requestData = ['name' => 'Updated Brand'];
        $updatedResponseData = array_merge($this->mockResponseData, ['name' => 'Updated Brand']);
        $responseMock = $this->createMock(ResponseInterface::class);

        $this->mapperMock->expects($this->once())
            ->method('toRequestData')
            ->with($request)
            ->willReturn($requestData);

        $this->clientMock->expects($this->once())
            ->method('patch')
            ->with('/api/v1/stores/123/brands/456', $requestData)
            ->willReturn($responseMock);

        $this->clientMock->expects($this->once())
            ->method('decodeResponse')
            ->with($responseMock)
            ->willReturn($updatedResponseData);

        $updatedResponse = new BrandResponse(
            ldId: '/api/v1/stores/123/brands/456',
            ldType: LdType::Brand,
            uuid: Uuid::fromString('f47ac10b-58cc-4372-a567-0e02b2c3d479'),
            createdAt: new DateTimeImmutable('2024-01-25T12:00:00+00:00'),
            updatedAt: new DateTimeImmutable('2024-01-25T12:00:00+00:00'),
            name: 'Updated Brand',
            description: 'Test Description',
            image: '/api/v1/files/789',
            store: '/api/v1/stores/123'
        );

        $this->mapperMock->expects($this->once())
            ->method('fromResponse')
            ->with($updatedResponseData)
            ->willReturn($updatedResponse);

        $result = $this->resource->update($request);
        $this->assertSame($updatedResponse, $result);
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     */
    public function testDeleteRequiresIri(): void
    {
        $this->expectException(InvalidRequestException::class);
        $this->resource->delete(new BrandRequest(name: 'Test Brand'));
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testDeleteBrand(): void
    {
        $request = new BrandRequest(
            name: 'Test Brand',
            iri: '/api/v1/stores/123/brands/456'
        );

        $responseMock = $this->createMock(ResponseInterface::class);

        $this->clientMock->expects($this->once())
            ->method('delete')
            ->with('/api/v1/stores/123/brands/456')
            ->willReturn($responseMock);

        $this->resource->delete($request);
    }

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    protected function setUp(): void
    {
        /** @phpstan-ignore-next-line */
        $this->clientMock = $this->createMock(Client::class);
        /** @phpstan-ignore-next-line */
        $this->mapperMock = $this->createMock(ReflectionAttributeDataMapper::class);
        $this->resource = new BrandResource($this->clientMock, $this->mapperMock);

        $this->request = new BrandRequest(
            name: 'Test Brand',
            description: 'Test Description',
            image: '/api/v1/files/789',
            store: '/api/v1/stores/123'
        );

        $uuid = Uuid::fromString('f47ac10b-58cc-4372-a567-0e02b2c3d479');
        $createdAt = new DateTimeImmutable('2024-01-25T12:00:00+00:00');
        $updatedAt = new DateTimeImmutable('2024-01-25T12:00:00+00:00');

        $this->response = new BrandResponse(
            ldId: '/api/v1/stores/123/brands/456',
            ldType: LdType::Brand,
            uuid: $uuid,
            createdAt: $createdAt,
            updatedAt: $updatedAt,
            name: 'Test Brand',
            description: 'Test Description',
            image: '/api/v1/files/789',
            store: '/api/v1/stores/123'
        );

        $this->mockResponseData = [
            '@id' => '/api/v1/stores/123/brands/456',
            '@type' => 'Brand',
            'uuid' => 'f47ac10b-58cc-4372-a567-0e02b2c3d479',
            'name' => 'Test Brand',
            'store' => '/api/v1/stores/123',
            'description' => 'Test Description',
            'image' => '/api/v1/files/789',
            'createdAt' => '2024-01-25T12:00:00+00:00',
            'updatedAt' => '2024-01-25T12:00:00+00:00',
        ];

        $this->mockCollectionData = [
            '@context' => '/api/contexts/Brand',
            '@id' => '/api/v1/stores/123/brands',
            '@type' => 'Collection',
            'member' => [$this->mockResponseData],
            'totalItems' => 1,
            'view' => '/api/v1/stores/123/brands?page=1',
            'firstPage' => '/api/v1/stores/123/brands?page=1',
            'lastPage' => '/api/v1/stores/123/brands?page=1',
        ];

        $this->collectionResponse = new BrandCollectionResponse(
            context: '/api/contexts/Brand',
            id: '/api/v1/stores/123/brands',
            type: LdType::Collection,
            members: [$this->response],
            totalItems: 1,
            view: '/api/v1/stores/123/brands?page=1',
            firstPage: '/api/v1/stores/123/brands?page=1',
            lastPage: '/api/v1/stores/123/brands?page=1',
            nextPage: null,
            previousPage: null
        );
    }
}
