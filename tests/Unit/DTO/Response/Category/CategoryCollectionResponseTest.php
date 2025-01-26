<?php

namespace Tests\Unit\DTO\Response\Category;

use Apiera\Sdk\DTO\Response\AbstractCollectionResponse;
use Apiera\Sdk\DTO\Response\Category\CategoryCollectionResponse;
use Apiera\Sdk\DTO\Response\Category\CategoryResponse;
use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Interface\DTO\JsonLDCollectionInterface;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Symfony\Component\Uid\Uuid;

class CategoryCollectionResponseTest extends TestCase
{
    public function testInstanceOf(): void
    {
        $response = new CategoryCollectionResponse(
            context: '',
            id: '',
            type: LdType::Collection,
            members: [],
            totalItems: 0
        );

        $this->assertInstanceOf(CategoryCollectionResponse::class, $response);
        $this->assertInstanceOf(AbstractCollectionResponse::class, $response);
        $this->assertInstanceOf(JsonLDCollectionInterface::class, $response);
    }

    public function testClassIsCorrectlyDefined(): void
    {
        $reflection = new ReflectionClass(CategoryCollectionResponse::class);

        $this->assertTrue($reflection->isFinal(), 'Class should be final');
        $this->assertTrue($reflection->isReadonly(), 'Class should be readonly');
    }

    public function testConstructorAndGetters(): void
    {
        $categoryResponse = new CategoryResponse(
            id: '/api/v1/categories/123',
            type: LdType::Category,
            uuid: Uuid::v4(),
            createdAt: new DateTimeImmutable(),
            updatedAt: new DateTimeImmutable(),
            name: 'Electronics',
            store: '/api/v1/stores/321'
        );

        $response = new CategoryCollectionResponse(
            context: '/api/v1/contexts/Category',
            id: '/api/v1/categories',
            type: LdType::Collection,
            members: [$categoryResponse],
            totalItems: 1,
            view: '/api/v1/categories?page=1',
            firstPage: '/api/v1/categories?page=1',
            lastPage: '/api/v1/categories?page=1',
            nextPage: null,
            previousPage: null
        );

        $this->assertEquals('/api/v1/contexts/Category', $response->getLdContext());
        $this->assertEquals('/api/v1/categories', $response->getLdId());
        $this->assertEquals(LdType::Collection, $response->getLdType());
        $this->assertCount(1, $response->getMembers());
        $this->assertInstanceOf(CategoryResponse::class, $response->getMembers()[0]);
        $this->assertEquals(1, $response->getTotalItems());
        $this->assertEquals('/api/v1/categories?page=1', $response->getView());
        $this->assertEquals('/api/v1/categories?page=1', $response->getFirstPage());
        $this->assertEquals('/api/v1/categories?page=1', $response->getLastPage());
        $this->assertNull($response->getNextPage());
        $this->assertNull($response->getPreviousPage());
    }

    public function testConstructorWithMinimalParameters(): void
    {
        $response = new CategoryCollectionResponse(
            context: '/api/v1/contexts/Category',
            id: '/api/v1/categories',
            type: LdType::Collection,
            members: [],
            totalItems: 0
        );

        $this->assertEquals('/api/v1/contexts/Category', $response->getLdContext());
        $this->assertEquals('/api/v1/categories', $response->getLdId());
        $this->assertEquals(LdType::Collection, $response->getLdType());
        $this->assertEmpty($response->getMembers());
        $this->assertEquals(0, $response->getTotalItems());
        $this->assertNull($response->getView());
        $this->assertNull($response->getFirstPage());
        $this->assertNull($response->getLastPage());
        $this->assertNull($response->getNextPage());
        $this->assertNull($response->getPreviousPage());
    }
}