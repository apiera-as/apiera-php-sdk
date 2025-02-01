<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Response\Property;

use Apiera\Sdk\DTO\Response\AbstractCollectionResponse;
use Apiera\Sdk\DTO\Response\Property\PropertyCollectionResponse;
use Apiera\Sdk\DTO\Response\Property\PropertyResponse;
use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Interface\DTO\JsonLDCollectionInterface;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Symfony\Component\Uid\Uuid;

final class PropertyCollectionResponseTest extends TestCase
{
    public function testInstanceOf(): void
    {
        $response = new PropertyCollectionResponse(
            context: '',
            id: '',
            type: LdType::Collection,
            members: [],
            totalItems: 0
        );

        $this->assertInstanceOf(PropertyCollectionResponse::class, $response);
        $this->assertInstanceOf(AbstractCollectionResponse::class, $response);
        $this->assertInstanceOf(JsonLDCollectionInterface::class, $response);
    }

    public function testClassIsCorrectlyDefined(): void
    {
        $reflection = new ReflectionClass(PropertyCollectionResponse::class);

        $this->assertTrue($reflection->isReadonly(), 'Class should be readonly');
    }

    public function testConstructorAndGetters(): void
    {
        $propertyResponse = new PropertyResponse(
            ldId: '/api/v1/stores/321/properties/123',
            ldType: LdType::Property,
            uuid: Uuid::fromString('bfd2639c-7793-426a-a413-ea262e582208'),
            createdAt: new DateTimeImmutable('2021-01-01 00:00:00'),
            updatedAt: new DateTimeImmutable('2021-01-01 00:00:00'),
            name: 'Example property',
            store: '/api/v1/stores/321'
        );

        $response = new PropertyCollectionResponse(
            context: '/api/v1/contexts/Property',
            id: '/api/v1/stores/321/properties',
            type: LdType::Collection,
            members: [$propertyResponse],
            totalItems: 1,
            view: '/api/v1/stores/321/properties?page=1',
            firstPage: '/api/v1/stores/321/properties?page=1',
            lastPage: '/api/v1/stores/321/properties?page=1',
            nextPage: null,
            previousPage: null
        );

        $this->assertEquals('/api/v1/contexts/Property', $response->getLdContext());
        $this->assertEquals('/api/v1/stores/321/properties', $response->getLdId());
        $this->assertEquals(LdType::Collection, $response->getLdType());
        $this->assertCount(1, $response->getMembers());
        $this->assertInstanceOf(PropertyResponse::class, $response->getMembers()[0]);
        $this->assertEquals(1, $response->getTotalItems());
        $this->assertEquals('/api/v1/stores/321/properties?page=1', $response->getView());
        $this->assertEquals('/api/v1/stores/321/properties?page=1', $response->getFirstPage());
        $this->assertEquals('/api/v1/stores/321/properties?page=1', $response->getLastPage());
        $this->assertNull($response->getNextPage());
        $this->assertNull($response->getPreviousPage());
    }

    public function testConstructorWithMinimalParameters(): void
    {
        $response = new PropertyCollectionResponse(
            context: '/api/v1/contexts/Property',
            id: '/api/v1/stores/321/properties',
            type: LdType::Collection,
            members: [],
            totalItems: 0
        );

        $this->assertEquals('/api/v1/contexts/Property', $response->getLdContext());
        $this->assertEquals('/api/v1/stores/321/properties', $response->getLdId());
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
