<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Response\Brand;

use Apiera\Sdk\DTO\Response\AbstractResponse;
use Apiera\Sdk\DTO\Response\Brand\BrandResponse;
use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Interface\DTO\JsonLDInterface;
use Apiera\Sdk\Interface\DTO\ResponseInterface;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Symfony\Component\Uid\Uuid;

final class BrandResponseTest extends TestCase
{
    public function testInstanceOf(): void
    {
        $response = new BrandResponse(
            id: '',
            type: LdType::Brand,
            uuid: Uuid::v4(),
            createdAt: new DateTimeImmutable(),
            updatedAt: new DateTimeImmutable(),
            name: '',
            store: ''
        );

        $this->assertInstanceOf(BrandResponse::class, $response);
        $this->assertInstanceOf(AbstractResponse::class, $response);
        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertInstanceOf(JsonLDInterface::class, $response);
    }

    public function testClassIsCorrectlyDefined(): void
    {
        $reflection = new ReflectionClass(BrandResponse::class);

        $this->assertTrue($reflection->isReadonly(), 'Class should be readonly');
    }

    public function testPropertiesAreReadonly(): void
    {
        $reflection = new ReflectionClass(BrandResponse::class);
        $properties = $reflection->getProperties();

        foreach ($properties as $property) {
            $this->assertTrue($property->isReadonly(), sprintf('Property %s should be readonly', $property->getName()));
        }
    }

    public function testConstructorAndGetters(): void
    {
        $response = new BrandResponse(
            id: '/api/v1/brands/123',
            type: LdType::Brand,
            uuid: Uuid::fromString('bfd2639c-7793-426a-a413-ea262e582208'),
            createdAt: new DateTimeImmutable('2021-01-01 00:00:00'),
            updatedAt: new DateTimeImmutable('2021-01-01 00:00:00'),
            name: 'Apiera',
            description: 'A SaaS company',
            image: '/api/v1/files/789',
            store: '/api/v1/stores/321',
        );

        $this->assertEquals('/api/v1/brands/123', $response->getLdId());
        $this->assertEquals(LdType::Brand, $response->getLdType());
        $this->assertTrue(Uuid::isValid($response->getUuid()->toRfc4122()));
        $this->assertEquals('bfd2639c-7793-426a-a413-ea262e582208', $response->getUuid()->toRfc4122());
        $this->assertEquals(new DateTimeImmutable('2021-01-01 00:00:00'), $response->getCreatedAt());
        $this->assertEquals(new DateTimeImmutable('2021-01-01 00:00:00'), $response->getUpdatedAt());
        $this->assertEquals('Apiera', $response->getName());
        $this->assertEquals('/api/v1/stores/321', $response->getStore());
        $this->assertEquals('A SaaS company', $response->getDescription());
        $this->assertEquals('/api/v1/files/789', $response->getImage());
    }

    public function testConstructorWithMinimalParameters(): void
    {
        $response = new BrandResponse(
            id: '/api/v1/brands/123',
            type: LdType::Brand,
            uuid: Uuid::fromString('bfd2639c-7793-426a-a413-ea262e582208'),
            createdAt: new DateTimeImmutable('2021-01-01 00:00:00'),
            updatedAt: new DateTimeImmutable('2021-01-01 00:00:00'),
            name: 'Apiera',
            store: '/api/v1/stores/321'
        );

        $this->assertEquals('/api/v1/brands/123', $response->getLdId());
        $this->assertEquals(LdType::Brand, $response->getLdType());
        $this->assertTrue(Uuid::isValid($response->getUuid()->toRfc4122()));
        $this->assertEquals('bfd2639c-7793-426a-a413-ea262e582208', $response->getUuid()->toRfc4122());
        $this->assertEquals(new DateTimeImmutable('2021-01-01 00:00:00'), $response->getCreatedAt());
        $this->assertEquals(new DateTimeImmutable('2021-01-01 00:00:00'), $response->getUpdatedAt());
        $this->assertEquals('Apiera', $response->getName());
        $this->assertEquals('/api/v1/stores/321', $response->getStore());
        $this->assertNull($response->getDescription());
        $this->assertNull($response->getImage());
    }
}
