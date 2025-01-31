<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Response\Brand;

use Apiera\Sdk\DTO\Response\AbstractCollectionResponse;
use Apiera\Sdk\DTO\Response\Brand\BrandCollectionResponse;
use Apiera\Sdk\DTO\Response\Brand\BrandResponse;
use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Interface\DTO\JsonLDCollectionInterface;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Symfony\Component\Uid\Uuid;

final class BrandCollectionResponseTest extends TestCase
{
    public function testInstanceOf(): void
    {
        $response = new BrandCollectionResponse(
            context: '',
            id: '',
            type: LdType::Collection,
            members: [],
            totalItems: 0
        );

        $this->assertInstanceOf(BrandCollectionResponse::class, $response);
        $this->assertInstanceOf(AbstractCollectionResponse::class, $response);
        $this->assertInstanceOf(JsonLDCollectionInterface::class, $response);
    }

    public function testClassIsCorrectlyDefined(): void
    {
        $reflection = new ReflectionClass(BrandCollectionResponse::class);

        $this->assertTrue($reflection->isReadonly(), 'Class should be readonly');
    }

    public function testConstructorAndGetters(): void
    {
        $brandResponse = new BrandResponse(
            id: '/api/v1/stores/123/brands/321',
            type: LdType::Brand,
            uuid: Uuid::v4(),
            createdAt: new DateTimeImmutable(),
            updatedAt: new DateTimeImmutable(),
            name: 'Apiera',
            store: '/api/v1/stores/123'
        );

        $response = new BrandCollectionResponse(
            context: '/api/v1/contexts/Brand',
            id: '/api/v1/stores/123/brands',
            type: LdType::Collection,
            members: [$brandResponse],
            totalItems: 1,
            view: '/api/v1/stores/123/brands?page=1',
            firstPage: '/api/v1/stores/123/brands?page=1',
            lastPage: '/api/v1/stores/123/brands?page=1',
            nextPage: null,
            previousPage: null
        );

        $this->assertEquals('/api/v1/contexts/Brand', $response->getLdContext());
        $this->assertEquals('/api/v1/stores/123/brands', $response->getLdId());
        $this->assertEquals(LdType::Collection, $response->getLdType());
        $this->assertCount(1, $response->getMembers());
        $this->assertInstanceOf(BrandResponse::class, $response->getMembers()[0]);
        $this->assertEquals(1, $response->getTotalItems());
        $this->assertEquals('/api/v1/stores/123/brands?page=1', $response->getView());
        $this->assertEquals('/api/v1/stores/123/brands?page=1', $response->getFirstPage());
        $this->assertEquals('/api/v1/stores/123/brands?page=1', $response->getLastPage());
        $this->assertNull($response->getNextPage());
        $this->assertNull($response->getPreviousPage());
    }

    public function testConstructorWithMinimalParameters(): void
    {
        $response = new BrandCollectionResponse(
            context: '/api/v1/contexts/Brand',
            id: '/api/v1/brands',
            type: LdType::Collection,
            members: [],
            totalItems: 0
        );

        $this->assertEquals('/api/v1/contexts/Brand', $response->getLdContext());
        $this->assertEquals('/api/v1/brands', $response->getLdId());
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
