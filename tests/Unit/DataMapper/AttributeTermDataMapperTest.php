<?php

declare(strict_types=1);

namespace Tests\Unit\DataMapper;

use Apiera\Sdk\DataMapper\AttributeTermDataMapper;
use Apiera\Sdk\DTO\Request\AttributeTerm\AttributeTermRequest;
use Apiera\Sdk\DTO\Response\AttributeTerm\AttributeTermCollectionResponse;
use Apiera\Sdk\DTO\Response\AttributeTerm\AttributeTermResponse;
use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Exception\ClientException;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

final class AttributeTermDataMapperTest extends TestCase
{
    private AttributeTermDataMapper $mapper;

    /** @var array<string, mixed> */
    private array $sampleResponseData;

    /** @var array<string, mixed> */
    private array $sampleCollectionData;
    private AttributeTermRequest $sampleRequest;

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     */
    public function testFromResponseMapsAllFieldsCorrectly(): void
    {
        $result = $this->mapper->fromResponse($this->sampleResponseData);

        $this->assertInstanceOf(AttributeTermResponse::class, $result);
        $this->assertEquals('/api/v1/stores/321/attributes/123/terms/456', $result->getLdId());
        $this->assertEquals(LdType::AttributeTerm, $result->getLdType());
        $this->assertEquals(Uuid::fromString('123e4567-e89b-12d3-a456-426614174000'), $result->getUuid());
        $this->assertInstanceOf(DateTimeImmutable::class, $result->getCreatedAt());
        $this->assertInstanceOf(DateTimeImmutable::class, $result->getUpdatedAt());
        $this->assertEquals('Example term', $result->getName());
        $this->assertEquals('/api/v1/stores/321/attributes/123', $result->getAttribute());
        $this->assertEquals('/api/v1/stores/321', $result->getStore());
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     */
    public function testFromCollectionResponseMapsDataCorrectly(): void
    {
        $result = $this->mapper->fromCollectionResponse($this->sampleCollectionData);

        $this->assertInstanceOf(AttributeTermCollectionResponse::class, $result);
        $this->assertEquals('/api/contexts/AttributeTerm', $result->getLdContext());
        $this->assertEquals('/api/v1/attributes/123/terms', $result->getLdId());
        $this->assertEquals(LdType::Collection, $result->getLdType());
        $this->assertEquals(1, $result->getTotalItems());
        $this->assertCount(1, $result->getMembers());
        $this->assertInstanceOf(AttributeTermResponse::class, $result->getMembers()[0]);
        $this->assertEquals('/api/v1/attributes/123/terms?page=1', $result->getView());
        $this->assertEquals('/api/v1/attributes/123/terms?page=1', $result->getFirstPage());
        $this->assertEquals('/api/v1/attributes/123/terms?page=1', $result->getLastPage());
        $this->assertNull($result->getNextPage());
        $this->assertNull($result->getPreviousPage());
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     */
    public function testFromResponseThrowsExceptionForInvalidDate(): void
    {
        $data = $this->sampleResponseData;
        $data['createdAt'] = 'invalid-date';

        $this->expectException(ClientException::class);
        $this->mapper->fromResponse($data);
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     */
    public function testFromCollectionResponseThrowsExceptionForInvalidType(): void
    {
        $data = $this->sampleCollectionData;
        $data['@type'] = 'InvalidType';

        $this->expectException(ClientException::class);
        $this->expectExceptionMessage('Invalid collection type');
        $this->mapper->fromCollectionResponse($data);
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     */
    public function testFromCollectionResponseHandlesEmptyCollection(): void
    {
        $data = $this->sampleCollectionData;
        $data['member'] = [];
        $data['totalItems'] = 0;

        $result = $this->mapper->fromCollectionResponse($data);

        $this->assertEmpty($result->getMembers());
        $this->assertEquals(0, $result->getTotalItems());
    }

    public function testToRequestDataIncludesRequiredFields(): void
    {
        $result = $this->mapper->toRequestData($this->sampleRequest);

        $expected = [
            'name' => 'Example term',
        ];

        $this->assertEquals($expected, $result);
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     */
    public function testFromCollectionResponseWithInvalidMemberThrowsException(): void
    {
        $data = $this->sampleCollectionData;
        $data['member'][0]['createdAt'] = 'invalid-date';

        $this->expectException(ClientException::class);
        $this->mapper->fromCollectionResponse($data);
    }

    protected function setUp(): void
    {
        $this->mapper = new AttributeTermDataMapper();

        $this->sampleResponseData = [
            '@id' => '/api/v1/stores/321/attributes/123/terms/456',
            '@type' => 'AttributeTerm',
            'uuid' => '123e4567-e89b-12d3-a456-426614174000',
            'createdAt' => '2025-01-01T00:00:00+00:00',
            'updatedAt' => '2025-01-01T00:00:00+00:00',
            'name' => 'Example term',
            'attribute' => '/api/v1/stores/321/attributes/123',
            'store' => '/api/v1/stores/321',
        ];

        $this->sampleCollectionData = [
            '@context' => '/api/contexts/AttributeTerm',
            '@id' => '/api/v1/attributes/123/terms',
            '@type' => 'Collection',
            'member' => [$this->sampleResponseData],
            'totalItems' => 1,
            'view' => '/api/v1/attributes/123/terms?page=1',
            'firstPage' => '/api/v1/attributes/123/terms?page=1',
            'lastPage' => '/api/v1/attributes/123/terms?page=1',
            'nextPage' => null,
            'previousPage' => null,
        ];

        $this->sampleRequest = new AttributeTermRequest(
            name: 'Example term',
            attribute: '/api/v1/stores/321/attributes/123'
        );
    }
}
