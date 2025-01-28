<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Response\Attribute;

use Apiera\Sdk\DTO\Response\AbstractCollectionResponse;
use Apiera\Sdk\DTO\Response\Attribute\AttributeCollectionResponse;
use Apiera\Sdk\DTO\Response\Attribute\AttributeResponse;
use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Interface\DTO\JsonLDCollectionInterface;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Symfony\Component\Uid\Uuid;

final class AttributeCollectionResponseTest extends TestCase
{
    public function testInstanceOf(): void
    {
        $response = new AttributeCollectionResponse(
            context: '',
            id: '',
            type: LdType::Collection,
            members: [],
            totalItems: 0
        );

        $this->assertInstanceOf(AttributeCollectionResponse::class, $response);
        $this->assertInstanceOf(AbstractCollectionResponse::class, $response);
        $this->assertInstanceOf(JsonLDCollectionInterface::class, $response);
    }

    public function testClassIsCorrectlyDefined(): void
    {
        $reflection = new ReflectionClass(AttributeCollectionResponse::class);

        $this->assertTrue($reflection->isReadonly(), 'Class should be readonly');
    }

    public function testConstructorAndGetters(): void
    {
        $attributeResponse = new AttributeResponse(
            id: '/api/v1/attributes/123',
            type: LdType::Attribute,
            uuid: Uuid::v4(),
            createdAt: new DateTimeImmutable(),
            updatedAt: new DateTimeImmutable(),
            name: 'Color',
            store: '/api/v1/stores/321'
        );

        $response = new AttributeCollectionResponse(
            context: '/api/v1/contexts/Attribute',
            id: '/api/v1/attributes',
            type: LdType::Collection,
            members: [$attributeResponse],
            totalItems: 1,
            view: '/api/v1/attributes?page=1',
            firstPage: '/api/v1/attributes?page=1',
            lastPage: '/api/v1/attributes?page=1',
            nextPage: null,
            previousPage: null
        );

        $this->assertEquals('/api/v1/contexts/Attribute', $response->getLdContext());
        $this->assertEquals('/api/v1/attributes', $response->getLdId());
        $this->assertEquals(LdType::Collection, $response->getLdType());
        $this->assertCount(1, $response->getMembers());
        $this->assertInstanceOf(AttributeResponse::class, $response->getMembers()[0]);
        $this->assertEquals(1, $response->getTotalItems());
        $this->assertEquals('/api/v1/attributes?page=1', $response->getView());
        $this->assertEquals('/api/v1/attributes?page=1', $response->getFirstPage());
        $this->assertEquals('/api/v1/attributes?page=1', $response->getLastPage());
        $this->assertNull($response->getNextPage());
        $this->assertNull($response->getPreviousPage());
    }

    public function testConstructorWithMinimalParameters(): void
    {
        $response = new AttributeCollectionResponse(
            context: '/api/v1/contexts/Attribute',
            id: '/api/v1/attributes',
            type: LdType::Collection,
            members: [],
            totalItems: 0
        );

        $this->assertEquals('/api/v1/contexts/Attribute', $response->getLdContext());
        $this->assertEquals('/api/v1/attributes', $response->getLdId());
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
