<?php

namespace Tests\Unit\DTO\Response\Attribute;

use Apiera\Sdk\DTO\Response\AbstractResponse;
use Apiera\Sdk\DTO\Response\Attribute\AttributeResponse;
use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Interface\DTO\JsonLDInterface;
use Apiera\Sdk\Interface\DTO\ResponseInterface;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Symfony\Component\Uid\Uuid;

class AttributeResponseTest extends TestCase
{
    public function testInstanceOf(): void
    {
        $response = new AttributeResponse(
            id: '',
            type: LdType::Attribute,
            uuid: Uuid::v4(),
            createdAt: new DateTimeImmutable(),
            updatedAt: new DateTimeImmutable(),
            name: '',
            store: ''
        );

        $this->assertInstanceOf(AttributeResponse::class, $response);
        $this->assertInstanceOf(AbstractResponse::class, $response);
        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertInstanceOf(JsonLDInterface::class, $response);
    }

    public function testClassIsCorrectlyDefined(): void
    {
        $reflection = new ReflectionClass(AttributeResponse::class);

        $this->assertTrue($reflection->isReadonly(), 'Class should be readonly');
    }

    public function testPropertiesAreReadonly(): void
    {
        $reflection = new ReflectionClass(AttributeResponse::class);
        $properties = $reflection->getProperties();

        foreach ($properties as $property) {
            $this->assertTrue($property->isReadonly(), sprintf('Property %s should be readonly', $property->getName()));
        }
    }

    public function testConstructorAndGetters(): void
    {
        $response = new AttributeResponse(
            id: '/api/v1/attributes/123',
            type: LdType::Attribute,
            uuid: Uuid::fromString('bfd2639c-7793-426a-a413-ea262e582208'),
            createdAt: new DateTimeImmutable('2021-01-01 00:00:00'),
            updatedAt: new DateTimeImmutable('2021-01-01 00:00:00'),
            name: 'Color',
            store: '/api/v1/stores/321'
        );

        $this->assertEquals('/api/v1/attributes/123', $response->getLdId());
        $this->assertEquals(LdType::Attribute, $response->getLdType());
        $this->assertTrue(Uuid::isValid($response->getUuid()->toRfc4122()));
        $this->assertEquals('bfd2639c-7793-426a-a413-ea262e582208', $response->getUuid()->toRfc4122());
        $this->assertEquals(new DateTimeImmutable('2021-01-01 00:00:00'), $response->getCreatedAt());
        $this->assertEquals(new DateTimeImmutable('2021-01-01 00:00:00'), $response->getUpdatedAt());
        $this->assertEquals('Color', $response->getName());
        $this->assertEquals('/api/v1/stores/321', $response->getStore());
    }
}