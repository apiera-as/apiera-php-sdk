<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Response\Distributor;

use Apiera\Sdk\DTO\Response\AbstractCollectionResponse;
use Apiera\Sdk\DTO\Response\Distributor\DistributorCollectionResponse;
use Apiera\Sdk\DTO\Response\Distributor\DistributorResponse;
use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Interface\DTO\JsonLDCollectionInterface;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Symfony\Component\Uid\Uuid;

final class DistributorCollectionResponseTest extends TestCase
{
    public function testInstanceOf(): void
    {
        $response = new DistributorCollectionResponse(
            context: '',
            id: '',
            type: LdType::Collection,
            members: [],
            totalItems: 0
        );

        $this->assertInstanceOf(DistributorCollectionResponse::class, $response);
        $this->assertInstanceOf(AbstractCollectionResponse::class, $response);
        $this->assertInstanceOf(JsonLDCollectionInterface::class, $response);
    }

    public function testClassIsCorrectlyDefined(): void
    {
        $reflection = new ReflectionClass(DistributorCollectionResponse::class);

        $this->assertTrue($reflection->isReadonly(), 'Class should be readonly');
    }

    public function testConstructorAndGetters(): void
    {
        $distributorResponse = new DistributorResponse(
            id: '/api/v1/stores/321/distributors/123',
            type: LdType::Distributor,
            uuid: Uuid::v4(),
            createdAt: new DateTimeImmutable(),
            updatedAt: new DateTimeImmutable(),
            name: 'Test distributor',
            store: '/api/v1/stores/321'
        );

        $response = new DistributorCollectionResponse(
            context: '/api/v1/contexts/Distributor',
            id: '/api/v1/distributors',
            type: LdType::Collection,
            members: [$distributorResponse],
            totalItems: 1,
            view: '/api/v1/distributors?page=1',
            firstPage: '/api/v1/distributors?page=1',
            lastPage: '/api/v1/distributors?page=1',
            nextPage: null,
            previousPage: null
        );

        $this->assertEquals('/api/v1/contexts/Distributor', $response->getLdContext());
        $this->assertEquals('/api/v1/distributors', $response->getLdId());
        $this->assertEquals(LdType::Collection, $response->getLdType());
        $this->assertCount(1, $response->getMembers());
        $this->assertInstanceOf(DistributorResponse::class, $response->getMembers()[0]);
        $this->assertEquals(1, $response->getTotalItems());
        $this->assertEquals('/api/v1/distributors?page=1', $response->getView());
        $this->assertEquals('/api/v1/distributors?page=1', $response->getFirstPage());
        $this->assertEquals('/api/v1/distributors?page=1', $response->getLastPage());
        $this->assertNull($response->getNextPage());
        $this->assertNull($response->getPreviousPage());
    }

    public function testConstructorWithMinimalParameters(): void
    {
        $response = new DistributorCollectionResponse(
            context: '/api/v1/contexts/Distributor',
            id: '/api/v1/distributors',
            type: LdType::Collection,
            members: [],
            totalItems: 0
        );

        $this->assertEquals('/api/v1/contexts/Distributor', $response->getLdContext());
        $this->assertEquals('/api/v1/distributors', $response->getLdId());
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
