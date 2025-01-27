<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Response\AlternateIdentifier;

use Apiera\Sdk\DTO\Response\AbstractCollectionResponse;
use Apiera\Sdk\DTO\Response\AlternateIdentifier\AlternateIdentifierCollectionResponse;
use Apiera\Sdk\DTO\Response\AlternateIdentifier\AlternateIdentifierResponse;
use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Interface\DTO\JsonLDCollectionInterface;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Symfony\Component\Uid\Uuid;

class AlternateIdentifierCollectionResponseTest extends TestCase
{
    public function testInstanceOf(): void
    {
        $response = new AlternateIdentifierCollectionResponse(
            context: '',
            id: '',
            type: LdType::Collection,
            members: [],
            totalItems: 0
        );

        $this->assertInstanceOf(AlternateIdentifierCollectionResponse::class, $response);
        $this->assertInstanceOf(AbstractCollectionResponse::class, $response);
        $this->assertInstanceOf(JsonLDCollectionInterface::class, $response);
    }

    public function testClassIsCorrectlyDefined(): void
    {
        $reflection = new ReflectionClass(AlternateIdentifierCollectionResponse::class);

        $this->assertTrue($reflection->isReadonly(), 'Class should be readonly');
    }

    public function testConstructorAndGetters(): void
    {
        $alternateIdentifierResponse = new AlternateIdentifierResponse(
            id: '/api/v1/alternate_identifiers/123',
            type: LdType::AlternateIdentifier,
            uuid: Uuid::v4(),
            createdAt: new DateTimeImmutable(),
            updatedAt: new DateTimeImmutable(),
            identifierType: 'gtin',
            code: 'ABC123'
        );

        $response = new AlternateIdentifierCollectionResponse(
            context: '/api/v1/contexts/AlternateIdentifier',
            id: '/api/v1/alternate_identifiers',
            type: LdType::Collection,
            members: [$alternateIdentifierResponse],
            totalItems: 1,
            view: '/api/v1/alternate_identifiers?page=1',
            firstPage: '/api/v1/alternate_identifiers?page=1',
            lastPage: '/api/v1/alternate_identifiers?page=1',
            nextPage: null,
            previousPage: null
        );

        $this->assertEquals('/api/v1/contexts/AlternateIdentifier', $response->getLdContext());
        $this->assertEquals('/api/v1/alternate_identifiers', $response->getLdId());
        $this->assertEquals(LdType::Collection, $response->getLdType());
        $this->assertCount(1, $response->getMembers());
        $this->assertInstanceOf(AlternateIdentifierResponse::class, $response->getMembers()[0]);
        $this->assertEquals(1, $response->getTotalItems());
        $this->assertEquals('/api/v1/alternate_identifiers?page=1', $response->getView());
        $this->assertEquals('/api/v1/alternate_identifiers?page=1', $response->getFirstPage());
        $this->assertEquals('/api/v1/alternate_identifiers?page=1', $response->getLastPage());
        $this->assertNull($response->getNextPage());
        $this->assertNull($response->getPreviousPage());
    }

    public function testConstructorWithMinimalParameters(): void
    {
        $response = new AlternateIdentifierCollectionResponse(
            context: '/api/v1/contexts/AlternateIdentifier',
            id: '/api/v1/alternate_identifiers',
            type: LdType::Collection,
            members: [],
            totalItems: 0
        );

        $this->assertEquals('/api/v1/contexts/AlternateIdentifier', $response->getLdContext());
        $this->assertEquals('/api/v1/alternate_identifiers', $response->getLdId());
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
