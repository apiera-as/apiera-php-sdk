<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Response\AttributeTerm;

use Apiera\Sdk\DTO\Response\AbstractResponse;
use Apiera\Sdk\DTO\Response\AttributeTerm\AttributeTermResponse;
use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Interface\DTO\JsonLDInterface;
use Apiera\Sdk\Interface\DTO\ResponseInterface;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Symfony\Component\Uid\Uuid;

final class AttributeTermResponseTest extends TestCase
{
    public function testInstanceOf(): void
    {
        $response = new AttributeTermResponse(
            ldId: '',
            ldType: LdType::AttributeTerm,
            uuid: Uuid::v4(),
            createdAt: new DateTimeImmutable(),
            updatedAt: new DateTimeImmutable(),
            name: '',
            attribute: '',
            store: ''
        );

        $this->assertInstanceOf(AttributeTermResponse::class, $response);
        $this->assertInstanceOf(AbstractResponse::class, $response);
        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertInstanceOf(JsonLDInterface::class, $response);
    }

    public function testClassIsCorrectlyDefined(): void
    {
        $reflection = new ReflectionClass(AttributeTermResponse::class);

        $this->assertTrue($reflection->isReadonly(), 'Class should be readonly');
    }

    public function testPropertiesAreReadonly(): void
    {
        $reflection = new ReflectionClass(AttributeTermResponse::class);
        $properties = $reflection->getProperties();

        foreach ($properties as $property) {
            $this->assertTrue($property->isReadonly(), sprintf('Property %s should be readonly', $property->getName()));
        }
    }

    public function testConstructorAndGetters(): void
    {
        $response = new AttributeTermResponse(
            ldId: '/api/v1/stores/321/attributes/123/terms/456',
            ldType: LdType::AttributeTerm,
            uuid: Uuid::fromString('bfd2639c-7793-426a-a413-ea262e582208'),
            createdAt: new DateTimeImmutable('2021-01-01 00:00:00'),
            updatedAt: new DateTimeImmutable('2021-01-01 00:00:00'),
            name: 'Example term',
            attribute: '/api/v1/stores/321/attributes/123',
            store: '/api/v1/stores/321'
        );

        $this->assertEquals('/api/v1/stores/321/attributes/123/terms/456', $response->getLdId());
        $this->assertEquals(LdType::AttributeTerm, $response->getLdType());
        $this->assertTrue(Uuid::isValid($response->getUuid()->toRfc4122()));
        $this->assertEquals('bfd2639c-7793-426a-a413-ea262e582208', $response->getUuid()->toRfc4122());
        $this->assertEquals(new DateTimeImmutable('2021-01-01 00:00:00'), $response->getCreatedAt());
        $this->assertEquals(new DateTimeImmutable('2021-01-01 00:00:00'), $response->getUpdatedAt());
        $this->assertEquals('Example term', $response->getName());
        $this->assertEquals('/api/v1/stores/321/attributes/123', $response->getAttribute());
        $this->assertEquals('/api/v1/stores/321', $response->getStore());
    }
}
