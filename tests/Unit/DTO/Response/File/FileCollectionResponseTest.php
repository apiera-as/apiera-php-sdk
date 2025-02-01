<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Response\File;

use Apiera\Sdk\DTO\Response\AbstractCollectionResponse;
use Apiera\Sdk\DTO\Response\File\FileCollectionResponse;
use Apiera\Sdk\DTO\Response\File\FileResponse;
use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Interface\DTO\JsonLDCollectionInterface;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Symfony\Component\Uid\Uuid;

final class FileCollectionResponseTest extends TestCase
{
    public function testInstanceOf(): void
    {
        $response = new FileCollectionResponse(
            context: '',
            id: '',
            type: LdType::Collection,
            members: [],
            totalItems: 0
        );

        $this->assertInstanceOf(FileCollectionResponse::class, $response);
        $this->assertInstanceOf(AbstractCollectionResponse::class, $response);
        $this->assertInstanceOf(JsonLDCollectionInterface::class, $response);
    }

    public function testClassIsCorrectlyDefined(): void
    {
        $reflection = new ReflectionClass(FileCollectionResponse::class);

        $this->assertTrue($reflection->isReadonly(), 'Class should be readonly');
    }

    public function testConstructorAndGetters(): void
    {
        $fileResponse = new FileResponse(
            ldId: '/api/v1/files/123',
            ldType: LdType::File,
            uuid: Uuid::v4(),
            createdAt: new DateTimeImmutable(),
            updatedAt: new DateTimeImmutable(),
            url: 'https://example.com/file.pdf',
            name: 'test-file.pdf'
        );

        $response = new FileCollectionResponse(
            context: '/api/v1/contexts/File',
            id: '/api/v1/files',
            type: LdType::Collection,
            members: [$fileResponse],
            totalItems: 1,
            view: '/api/v1/files?page=1',
            firstPage: '/api/v1/files?page=1',
            lastPage: '/api/v1/files?page=1',
            nextPage: null,
            previousPage: null
        );

        $this->assertEquals('/api/v1/contexts/File', $response->getLdContext());
        $this->assertEquals('/api/v1/files', $response->getLdId());
        $this->assertEquals(LdType::Collection, $response->getLdType());
        $this->assertCount(1, $response->getMembers());
        $this->assertInstanceOf(FileResponse::class, $response->getMembers()[0]);
        $this->assertEquals(1, $response->getTotalItems());
        $this->assertEquals('/api/v1/files?page=1', $response->getView());
        $this->assertEquals('/api/v1/files?page=1', $response->getFirstPage());
        $this->assertEquals('/api/v1/files?page=1', $response->getLastPage());
        $this->assertNull($response->getNextPage());
        $this->assertNull($response->getPreviousPage());
    }

    public function testConstructorWithMinimalParameters(): void
    {
        $response = new FileCollectionResponse(
            context: '/api/v1/contexts/File',
            id: '/api/v1/files',
            type: LdType::Collection,
            members: [],
            totalItems: 0
        );

        $this->assertEquals('/api/v1/contexts/File', $response->getLdContext());
        $this->assertEquals('/api/v1/files', $response->getLdId());
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
