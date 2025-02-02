<?php

declare(strict_types=1);

namespace Tests\Unit\DataMapper;

use Apiera\Sdk\DataMapper\ReflectionAttributeDataMapper;
use Apiera\Sdk\DTO\Request\Attribute\AttributeRequest;
use Apiera\Sdk\DTO\Response\Attribute\AttributeCollectionResponse;
use Apiera\Sdk\DTO\Response\Attribute\AttributeResponse;
use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Exception\Mapping\ResponseMappingException;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

final class AttributeDataMapperTest extends TestCase
{
    private ReflectionAttributeDataMapper $mapper;

    /** @var array<string, mixed> */
    private array $sampleResponseData;

    /** @var array<string, mixed> */
    private array $sampleCollectionData;
    private AttributeRequest $sampleRequest;

    /**
     * @throws \Apiera\Sdk\Exception\Mapping\ResponseMappingException
     */
    public function testFromResponseMapsAllFieldsCorrectly(): void
    {
        $result = $this->mapper->fromResponse($this->sampleResponseData);

        $this->assertInstanceOf(AttributeResponse::class, $result);
        $this->assertEquals('/api/attributes/123', $result->getLdId());
        $this->assertEquals(LdType::Attribute, $result->getLdType());
        $this->assertEquals(Uuid::fromString('123e4567-e89b-12d3-a456-426614174000'), $result->getUuid());
        $this->assertInstanceOf(DateTimeImmutable::class, $result->getCreatedAt());
        $this->assertInstanceOf(DateTimeImmutable::class, $result->getUpdatedAt());
        $this->assertEquals('Test Attribute', $result->getName());
        $this->assertEquals('/api/stores/123', $result->getStore());
    }

    /**
     * @throws \Apiera\Sdk\Exception\Mapping\ResponseMappingException
     */
    public function testFromResponseThrowsExceptionForInvalidDate(): void
    {
        $data = $this->sampleResponseData;
        $data['createdAt'] = 'invalid-date';

        $this->expectException(ResponseMappingException::class);
        $this->mapper->fromResponse($data);
    }

    /**
     * @throws \Apiera\Sdk\Exception\Mapping\ResponseMappingException
     */
    public function testFromCollectionResponseThrowsExceptionForInvalidType(): void
    {
        $data = $this->sampleCollectionData;
        $data['@type'] = 'InvalidType';

        $this->expectException(ResponseMappingException::class);
        $this->expectExceptionMessage('Failed to map collection data');
        $this->mapper->fromCollectionResponse($data);
    }

    /**
     * @throws \Apiera\Sdk\Exception\Mapping\ResponseMappingException
     */
    public function testFromCollectionResponseMapsDataCorrectly(): void
    {
        $result = $this->mapper->fromCollectionResponse($this->sampleCollectionData);

        $this->assertInstanceOf(AttributeCollectionResponse::class, $result);
        $this->assertEquals('/api/contexts/Attribute', $result->getLdContext());
        $this->assertEquals('/api/attributes', $result->getLdId());
        $this->assertEquals(LdType::Collection, $result->getLdType());
        $this->assertEquals(1, $result->getTotalItems());
        $this->assertCount(1, $result->getMembers());
        $this->assertInstanceOf(AttributeResponse::class, $result->getMembers()[0]);
        $this->assertEquals('/api/attributes?page=1', $result->getView());
        $this->assertEquals('/api/attributes?page=1', $result->getFirstPage());
        $this->assertEquals('/api/attributes?page=1', $result->getLastPage());
        $this->assertNull($result->getNextPage());
        $this->assertNull($result->getPreviousPage());
    }

    /**
     * @throws \Apiera\Sdk\Exception\Mapping\ResponseMappingException
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

    /**
     * @throws \Apiera\Sdk\Exception\Mapping\RequestMappingException
     */
    public function testToRequestDataIncludesRequiredFields(): void
    {
        $result = $this->mapper->toRequestData($this->sampleRequest);

        $expected = [
            'name' => 'Test Attribute',
        ];

        $this->assertEquals($expected, $result);
    }

    /**
     * @throws \Apiera\Sdk\Exception\Mapping\ResponseMappingException
     */
    public function testFromCollectionResponseWithInvalidMemberThrowsException(): void
    {
        $data = $this->sampleCollectionData;
        $data['member'][0]['createdAt'] = 'invalid-date';

        $this->expectException(ResponseMappingException::class);
        $this->mapper->fromCollectionResponse($data);
    }

    protected function setUp(): void
    {
        $this->mapper = new ReflectionAttributeDataMapper();

        $this->sampleResponseData = [
            '@id' => '/api/attributes/123',
            '@type' => 'Attribute',
            'uuid' => '123e4567-e89b-12d3-a456-426614174000',
            'createdAt' => '2025-01-01T00:00:00+00:00',
            'updatedAt' => '2025-01-01T00:00:00+00:00',
            'name' => 'Test Attribute',
            'store' => '/api/stores/123',
        ];

        $this->sampleCollectionData = [
            '@context' => '/api/contexts/Attribute',
            '@id' => '/api/attributes',
            '@type' => 'Collection',
            'member' => [$this->sampleResponseData],
            'totalItems' => 1,
            'view' => '/api/attributes?page=1',
            'firstPage' => '/api/attributes?page=1',
            'lastPage' => '/api/attributes?page=1',
            'nextPage' => null,
            'previousPage' => null,
        ];

        $this->sampleRequest = new AttributeRequest(
            name: 'Test Attribute',
            store: '/api/stores/123'
        );
    }
}
