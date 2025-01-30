<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Response\AttributeTerm;

use Apiera\Sdk\DTO\Response\AbstractCollectionResponse;
use Apiera\Sdk\DTO\Response\AttributeTerm\AttributeTermCollectionResponse;
use Apiera\Sdk\DTO\Response\AttributeTerm\AttributeTermResponse;
use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Interface\DTO\JsonLDCollectionInterface;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Symfony\Component\Uid\Uuid;

final class AttributeTermCollectionResponseTest extends TestCase
{
    public function testInstanceOf(): void
    {
        $response = new AttributeTermCollectionResponse(
            context: '',
            id: '',
            type: LdType::Collection,
            members: [],
            totalItems: 0
        );

        $this->assertInstanceOf(AttributeTermCollectionResponse::class, $response);
        $this->assertInstanceOf(AbstractCollectionResponse::class, $response);
        $this->assertInstanceOf(JsonLDCollectionInterface::class, $response);
    }

    public function testClassIsCorrectlyDefined(): void
    {
        $reflection = new ReflectionClass(AttributeTermCollectionResponse::class);

        $this->assertTrue($reflection->isReadonly(), 'Class should be readonly');
    }

    public function testConstructorAndGetters(): void
    {
        $attributeTermResponse = new AttributeTermResponse(
            id: '/api/v1/stores/321/attributes/123/terms/456',
            type: LdType::AttributeTerm,
            uuid: Uuid::fromString('bfd2639c-7793-426a-a413-ea262e582208'),
            createdAt: new DateTimeImmutable('2021-01-01 00:00:00'),
            updatedAt: new DateTimeImmutable('2021-01-01 00:00:00'),
            name: 'Example term',
            attribute: '/api/v1/stores/321/attributes/123',
            store: '/api/v1/stores/321'
        );

        $response = new AttributeTermCollectionResponse(
            context: '/api/v1/contexts/AttributeTerm',
            id: '/api/v1/attributes/123/terms',
            type: LdType::Collection,
            members: [$attributeTermResponse],
            totalItems: 1,
            view: '/api/v1/attributes/123/terms?page=1',
            firstPage: '/api/v1/attributes/123/terms?page=1',
            lastPage: '/api/v1/attributes/123/terms?page=1',
            nextPage: null,
            previousPage: null
        );

        $this->assertEquals('/api/v1/contexts/AttributeTerm', $response->getLdContext());
        $this->assertEquals('/api/v1/attributes/123/terms', $response->getLdId());
        $this->assertEquals(LdType::Collection, $response->getLdType());
        $this->assertCount(1, $response->getMembers());
        $this->assertInstanceOf(AttributeTermResponse::class, $response->getMembers()[0]);
        $this->assertEquals(1, $response->getTotalItems());
        $this->assertEquals('/api/v1/attributes/123/terms?page=1', $response->getView());
        $this->assertEquals('/api/v1/attributes/123/terms?page=1', $response->getFirstPage());
        $this->assertEquals('/api/v1/attributes/123/terms?page=1', $response->getLastPage());
        $this->assertNull($response->getNextPage());
        $this->assertNull($response->getPreviousPage());
    }

    public function testConstructorWithMinimalParameters(): void
    {
        $response = new AttributeTermCollectionResponse(
            context: '/api/v1/contexts/AttributeTerm',
            id: '/api/v1/attributes/123/terms',
            type: LdType::Collection,
            members: [],
            totalItems: 0
        );

        $this->assertEquals('/api/v1/contexts/AttributeTerm', $response->getLdContext());
        $this->assertEquals('/api/v1/attributes/123/terms', $response->getLdId());
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
