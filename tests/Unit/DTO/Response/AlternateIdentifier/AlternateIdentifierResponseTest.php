<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Response\AlternateIdentifier;

use Apiera\Sdk\DTO\Response\AbstractResponse;
use Apiera\Sdk\DTO\Response\AlternateIdentifier\AlternateIdentifierResponse;
use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Interface\DTO\JsonLDInterface;
use Apiera\Sdk\Interface\DTO\ResponseInterface;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Symfony\Component\Uid\Uuid;

class AlternateIdentifierResponseTest extends TestCase
{
    public function testInstanceOf(): void
    {
        $response = new AlternateIdentifierResponse(
            id: '',
            type: LdType::AlternateIdentifier,
            uuid: Uuid::v4(),
            createdAt: new DateTimeImmutable(),
            updatedAt: new DateTimeImmutable(),
            identifierType: '',
            code: ''
        );

        $this->assertInstanceOf(AlternateIdentifierResponse::class, $response);
        $this->assertInstanceOf(AbstractResponse::class, $response);
        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertInstanceOf(JsonLDInterface::class, $response);
    }

    public function testClassIsCorrectlyDefined(): void
    {
        $reflection = new ReflectionClass(AlternateIdentifierResponse::class);

        $this->assertTrue($reflection->isReadonly(), 'Class should be readonly');
    }

    public function testPropertiesAreReadonly(): void
    {
        $reflection = new ReflectionClass(AlternateIdentifierResponse::class);
        $properties = $reflection->getProperties();

        foreach ($properties as $property) {
            $this->assertTrue($property->isReadonly(), sprintf('Property %s should be readonly', $property->getName()));
        }
    }

    public function testConstructorAndGetters(): void
    {
        $response = new AlternateIdentifierResponse(
            id: '/api/v1/alternate_identifiers/123',
            type: LdType::AlternateIdentifier,
            uuid: Uuid::fromString('bfd2639c-7793-426a-a413-ea262e582208'),
            createdAt: new DateTimeImmutable('2021-01-01 00:00:00'),
            updatedAt: new DateTimeImmutable('2021-01-01 00:00:00'),
            identifierType: 'gtin',
            code: 'ABC123'
        );

        $this->assertEquals('/api/v1/alternate_identifiers/123', $response->getLdId());
        $this->assertEquals(LdType::AlternateIdentifier, $response->getLdType());
        $this->assertTrue(Uuid::isValid($response->getUuid()->toRfc4122()));
        $this->assertEquals('bfd2639c-7793-426a-a413-ea262e582208', $response->getUuid()->toRfc4122());
        $this->assertEquals(new DateTimeImmutable('2021-01-01 00:00:00'), $response->getCreatedAt());
        $this->assertEquals(new DateTimeImmutable('2021-01-01 00:00:00'), $response->getUpdatedAt());
        $this->assertEquals('gtin', $response->getIdentifierType());
        $this->assertEquals('ABC123', $response->getCode());
    }
}
