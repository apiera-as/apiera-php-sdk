<?php

declare(strict_types=1);

namespace Tests\Unit\DataMapper;

use Apiera\Sdk\DataMapper\CategoryDataMapper;
use Apiera\Sdk\DTO\Request\Category\CategoryRequest;
use Apiera\Sdk\DTO\Response\Category\CategoryCollectionResponse;
use Apiera\Sdk\DTO\Response\Category\CategoryResponse;
use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Exception\ClientException;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

final class CategoryDataMapperTest extends TestCase
{
    private CategoryDataMapper $mapper;

    /** @var array<string, mixed> */
    private array $sampleResponseData;

    /** @var array<string, mixed> */
    private array $sampleCollectionData;
    private CategoryRequest $sampleRequest;

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     */
    public function testFromResponseMapsAllFieldsCorrectly(): void
    {
        $result = $this->mapper->fromResponse($this->sampleResponseData);

        $this->assertInstanceOf(CategoryResponse::class, $result);
        $this->assertEquals('/api/categories/123', $result->getLdId());
        $this->assertEquals(LdType::Category, $result->getLdType());
        $this->assertEquals(Uuid::fromString('123e4567-e89b-12d3-a456-426614174000'), $result->getUuid());
        $this->assertInstanceOf(DateTimeImmutable::class, $result->getCreatedAt());
        $this->assertInstanceOf(DateTimeImmutable::class, $result->getUpdatedAt());
        $this->assertEquals('Test Category', $result->getName());
        $this->assertEquals('/api/stores/123', $result->getStore());
        $this->assertEquals('Test Description', $result->getDescription());
        $this->assertEquals('/api/categories/parent', $result->getParent());
        $this->assertEquals('/api/files/123', $result->getImage());
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     */
    public function testFromResponseHandlesNullableFieldsCorrectly(): void
    {
        $data = $this->sampleResponseData;
        $data['description'] = null;
        $data['parent'] = null;
        $data['image'] = null;

        $result = $this->mapper->fromResponse($data);

        $this->assertNull($result->getDescription());
        $this->assertNull($result->getParent());
        $this->assertNull($result->getImage());
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     */
    public function testFromResponseThrowsExceptionForInvalidDate(): void
    {
        $data = $this->sampleResponseData;
        $data['createdAt'] = 'invalid-date';

        $this->expectException(ClientException::class);
        $this->mapper->fromResponse($data);
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     */
    public function testFromCollectionResponseThrowsExceptionForInvalidType(): void
    {
        $data = $this->sampleCollectionData;
        $data['@type'] = 'InvalidType';

        $this->expectException(ClientException::class);
        $this->expectExceptionMessage('Invalid collection type');
        $this->mapper->fromCollectionResponse($data);
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     */
    public function testFromCollectionResponseMapsDataCorrectly(): void
    {
        $result = $this->mapper->fromCollectionResponse($this->sampleCollectionData);

        $this->assertInstanceOf(CategoryCollectionResponse::class, $result);
        $this->assertEquals('/api/contexts/Category', $result->getLdContext());
        $this->assertEquals('/api/categories', $result->getLdId());
        $this->assertEquals(LdType::Collection, $result->getLdType());
        $this->assertEquals(1, $result->getTotalItems());
        $this->assertCount(1, $result->getMembers());
        $this->assertInstanceOf(CategoryResponse::class, $result->getMembers()[0]);
        $this->assertEquals('/api/categories?page=1', $result->getView());
        $this->assertEquals('/api/categories?page=1', $result->getFirstPage());
        $this->assertEquals('/api/categories?page=1', $result->getLastPage());
        $this->assertNull($result->getNextPage());
        $this->assertNull($result->getPreviousPage());
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     */
    public function testFromCollectionResponseHandlesEmptyCollection(): void
    {
        $data = $this->sampleCollectionData;
        $data['member'] = [];
        $data['totalItems'] = 0;

        $result = $this->mapper->fromCollectionResponse($data);

        $this->assertEmpty($result->getMembers());
        $this->assertEquals(0, $result->getTotalItems());
    }

    public function testToRequestDataIncludesRequiredFields(): void
    {
        $result = $this->mapper->toRequestData($this->sampleRequest);

        $expected = [
            'name' => 'Test Category',
            'description' => 'Test Description',
            'parent' => '/api/categories/parent',
            'image' => '/api/files/123',
        ];

        $this->assertEquals($expected, $result);
    }

    public function testToRequestDataHandlesNullFields(): void
    {
        $request = new CategoryRequest(
            name: 'Test Category'
        );

        $result = $this->mapper->toRequestData($request);

        $expected = [
            'name' => 'Test Category',
            'description' => null,
            'parent' => null,
            'image' => null,
        ];

        $this->assertEquals($expected, $result);
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     */
    public function testFromCollectionResponseWithInvalidMemberThrowsException(): void
    {
        $data = $this->sampleCollectionData;
        $data['member'][0]['createdAt'] = 'invalid-date';

        $this->expectException(ClientException::class);
        $this->mapper->fromCollectionResponse($data);
    }

    protected function setUp(): void
    {
        $this->mapper = new CategoryDataMapper();

        // Sample response data
        $this->sampleResponseData = [
            '@id' => '/api/categories/123',
            '@type' => 'Category',
            'uuid' => '123e4567-e89b-12d3-a456-426614174000',
            'createdAt' => '2025-01-01T00:00:00+00:00',
            'updatedAt' => '2025-01-01T00:00:00+00:00',
            'name' => 'Test Category',
            'store' => '/api/stores/123',
            'description' => 'Test Description',
            'parent' => '/api/categories/parent',
            'image' => '/api/files/123',
        ];

        // Sample collection data
        $this->sampleCollectionData = [
            '@context' => '/api/contexts/Category',
            '@id' => '/api/categories',
            '@type' => 'Collection',
            'member' => [$this->sampleResponseData],
            'totalItems' => 1,
            'view' => '/api/categories?page=1',
            'firstPage' => '/api/categories?page=1',
            'lastPage' => '/api/categories?page=1',
            'nextPage' => null,
            'previousPage' => null,
        ];

        // Sample request
        $this->sampleRequest = new CategoryRequest(
            name: 'Test Category',
            store: '/api/stores/123',
            description: 'Test Description',
            parent: '/api/categories/parent',
            image: '/api/files/123'
        );
    }
}
