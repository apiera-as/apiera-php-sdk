<?php

declare(strict_types=1);

namespace Tests\Unit\DataMapper;

use Apiera\Sdk\DataMapper\BrandDataMapper;
use Apiera\Sdk\DTO\Request\Brand\BrandRequest;
use Apiera\Sdk\DTO\Response\Brand\BrandCollectionResponse;
use Apiera\Sdk\DTO\Response\Brand\BrandResponse;
use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Exception\ClientException;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

final class BrandDataMapperTest extends TestCase
{
    private BrandDataMapper $mapper;

    /** @var array<string, mixed> */
    private array $sampleResponseData;

    /** @var array<string, mixed> */
    private array $sampleCollectionData;
    private BrandRequest $sampleRequest;

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     */
    public function testFromResponseMapsAllFieldsCorrectly(): void
    {
        $result = $this->mapper->fromResponse($this->sampleResponseData);

        $this->assertInstanceOf(BrandResponse::class, $result);
        $this->assertEquals('/api/v1/stores/123/brands/321', $result->getLdId());
        $this->assertEquals(LdType::Brand, $result->getLdType());
        $this->assertEquals(Uuid::fromString('123e4567-e89b-12d3-a456-426614174000'), $result->getUuid());
        $this->assertInstanceOf(DateTimeImmutable::class, $result->getCreatedAt());
        $this->assertInstanceOf(DateTimeImmutable::class, $result->getUpdatedAt());
        $this->assertEquals('Test Brand', $result->getName());
        $this->assertEquals('/api/v1/stores/123', $result->getStore());
        $this->assertEquals('Test Description', $result->getDescription());
        $this->assertEquals('/api/v1/files/123', $result->getImage());
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     */
    public function testFromResponseHandlesNullableFieldsCorrectly(): void
    {
        $data = $this->sampleResponseData;
        $data['description'] = null;
        $data['image'] = null;

        $result = $this->mapper->fromResponse($data);

        $this->assertNull($result->getDescription());
        $this->assertNull($result->getImage());
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
    public function testFromCollectionResponseMapsDataCorrectly(): void
    {
        $result = $this->mapper->fromCollectionResponse($this->sampleCollectionData);

        $this->assertInstanceOf(BrandCollectionResponse::class, $result);
        $this->assertEquals('/api/contexts/Brand', $result->getLdContext());
        $this->assertEquals('/api/v1/stores/123/brands', $result->getLdId());
        $this->assertEquals(LdType::Collection, $result->getLdType());
        $this->assertEquals(1, $result->getTotalItems());
        $this->assertCount(1, $result->getMembers());
        $this->assertInstanceOf(BrandResponse::class, $result->getMembers()[0]);
        $this->assertEquals('/api/v1/stores/123/brands?page=1', $result->getView());
        $this->assertEquals('/api/v1/stores/123/brands?page=1', $result->getFirstPage());
        $this->assertEquals('/api/v1/stores/123/brands?page=1', $result->getLastPage());
        $this->assertNull($result->getNextPage());
        $this->assertNull($result->getPreviousPage());
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
            'name' => 'Test Brand',
            'description' => 'Test Description',
            'image' => '/api/v1/files/123',
        ];

        $this->assertEquals($expected, $result);
    }

    public function testToRequestDataHandlesNullFields(): void
    {
        $request = new BrandRequest(
            name: 'Test Brand'
        );

        $result = $this->mapper->toRequestData($request);

        $expected = [
            'name' => 'Test Brand',
            'description' => null,
            'image' => null,
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
        $this->mapper = new BrandDataMapper();

        $this->sampleResponseData = [
            '@id' => '/api/v1/stores/123/brands/321',
            '@type' => 'Brand',
            'uuid' => '123e4567-e89b-12d3-a456-426614174000',
            'createdAt' => '2025-01-01T00:00:00+00:00',
            'updatedAt' => '2025-01-01T00:00:00+00:00',
            'name' => 'Test Brand',
            'store' => '/api/v1/stores/123',
            'description' => 'Test Description',
            'image' => '/api/v1/files/123',
        ];

        $this->sampleCollectionData = [
            '@context' => '/api/contexts/Brand',
            '@id' => '/api/v1/stores/123/brands',
            '@type' => 'Collection',
            'member' => [$this->sampleResponseData],
            'totalItems' => 1,
            'view' => '/api/v1/stores/123/brands?page=1',
            'firstPage' => '/api/v1/stores/123/brands?page=1',
            'lastPage' => '/api/v1/stores/123/brands?page=1',
            'nextPage' => null,
            'previousPage' => null,
        ];

        $this->sampleRequest = new BrandRequest(
            name: 'Test Brand',
            description: 'Test Description',
            image: '/api/v1/files/123',
            store: '/api/v1/stores/123'
        );
    }
}
