<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Response\Category;

use Apiera\Sdk\DTO\Response\AbstractResponse;
use Apiera\Sdk\DTO\Response\Category\CategoryResponse;
use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Interface\DTO\JsonLDInterface;
use Apiera\Sdk\Interface\DTO\ResponseInterface;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Symfony\Component\Uid\Uuid;

final class CategoryResponseTest extends TestCase
{
    public function testInstanceOf(): void
    {
        $response = new CategoryResponse(
            ldId: '',
            ldType: LdType::Category,
            uuid: Uuid::v4(),
            createdAt: new DateTimeImmutable(),
            updatedAt: new DateTimeImmutable(),
            name: '',
            store: ''
        );

        $this->assertInstanceOf(CategoryResponse::class, $response);
        $this->assertInstanceOf(AbstractResponse::class, $response);
        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertInstanceOf(JsonLDInterface::class, $response);
    }

    public function testClassIsCorrectlyDefined(): void
    {
        $reflection = new ReflectionClass(CategoryResponse::class);

        $this->assertTrue($reflection->isReadonly(), 'Class should be readonly');
    }

    public function testPropertiesAreReadonly(): void
    {
        $reflection = new ReflectionClass(CategoryResponse::class);
        $properties = $reflection->getProperties();

        foreach ($properties as $property) {
            $this->assertTrue($property->isReadonly(), sprintf('Property %s should be readonly', $property->getName()));
        }
    }

    public function testConstructorAndGetters(): void
    {
        $response = new CategoryResponse(
            ldId: '/api/v1/categories/123',
            ldType: LdType::Category,
            uuid: Uuid::fromString('bfd2639c-7793-426a-a413-ea262e582208'),
            createdAt: new DateTimeImmutable('2021-01-01 00:00:00'),
            updatedAt: new DateTimeImmutable('2021-01-01 00:00:00'),
            name: 'Electronics',
            store: '/api/v1/stores/321',
            description: 'A category for electronics.',
            parent: '/api/v1/stores/321/categories/456',
            image: '/api/v1/files/789',
        );

        $this->assertEquals('/api/v1/categories/123', $response->getLdId());
        $this->assertEquals(LdType::Category, $response->getLdType());
        $this->assertTrue(Uuid::isValid($response->getUuid()->toRfc4122()));
        $this->assertEquals('bfd2639c-7793-426a-a413-ea262e582208', $response->getUuid()->toRfc4122());
        $this->assertEquals(new DateTimeImmutable('2021-01-01 00:00:00'), $response->getCreatedAt());
        $this->assertEquals(new DateTimeImmutable('2021-01-01 00:00:00'), $response->getUpdatedAt());
        $this->assertEquals('Electronics', $response->getName());
        $this->assertEquals('/api/v1/stores/321', $response->getStore());
        $this->assertEquals('A category for electronics.', $response->getDescription());
        $this->assertEquals('/api/v1/stores/321/categories/456', $response->getParent());
        $this->assertEquals('/api/v1/files/789', $response->getImage());
    }

    public function testConstructorWithMinimalParameters(): void
    {
        $response = new CategoryResponse(
            ldId: '/api/v1/categories/123',
            ldType: LdType::Category,
            uuid: Uuid::fromString('bfd2639c-7793-426a-a413-ea262e582208'),
            createdAt: new DateTimeImmutable('2021-01-01 00:00:00'),
            updatedAt: new DateTimeImmutable('2021-01-01 00:00:00'),
            name: 'Electronics',
            store: '/api/v1/stores/321'
        );

        $this->assertEquals('/api/v1/categories/123', $response->getLdId());
        $this->assertEquals(LdType::Category, $response->getLdType());
        $this->assertTrue(Uuid::isValid($response->getUuid()->toRfc4122()));
        $this->assertEquals('bfd2639c-7793-426a-a413-ea262e582208', $response->getUuid()->toRfc4122());
        $this->assertEquals(new DateTimeImmutable('2021-01-01 00:00:00'), $response->getCreatedAt());
        $this->assertEquals(new DateTimeImmutable('2021-01-01 00:00:00'), $response->getUpdatedAt());
        $this->assertEquals('Electronics', $response->getName());
        $this->assertEquals('/api/v1/stores/321', $response->getStore());
        $this->assertNull($response->getDescription());
        $this->assertNull($response->getParent());
        $this->assertNull($response->getImage());
    }
}
