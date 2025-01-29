<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Request\File;

use Apiera\Sdk\DTO\Request\File\FileRequest;
use Apiera\Sdk\Interface\DTO\RequestInterface;
use PHPUnit\Framework\TestCase;

final class FileRequestTest extends TestCase
{
    public function testInstanceOf(): void
    {
        $request = new FileRequest('', '');

        $this->assertInstanceOf(FileRequest::class, $request);
        $this->assertInstanceOf(RequestInterface::class, $request);
    }

    public function testConstructorAndGetters(): void
    {
        $request = new FileRequest(
            url: 'https://example.com/file.pdf',
            name: 'Example File',
            iri: '/api/v1/files/123'
        );

        $this->assertEquals('Example File', $request->getName());
        $this->assertEquals('https://example.com/file.pdf', $request->getUrl());
        $this->assertEquals('/api/v1/files/123', $request->getIri());
    }

    public function testConstructorWithMinimalParameters(): void
    {
        $request = new FileRequest(url: 'https://example.com/file.pdf');

        $this->assertEquals('https://example.com/file.pdf', $request->getUrl());
        $this->assertNull($request->getName());
        $this->assertNull($request->getIri());
    }

    public function testToArray(): void
    {
        $request = new FileRequest(
            url: 'https://example.com/file.pdf',
            name: 'Example File',
            iri: '/api/v1/files/123'
        );

        $array = $request->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('url', $array);
        $this->assertArrayHasKey('name', $array);
        $this->assertArrayNotHasKey('iri', $array);

        $this->assertEquals('https://example.com/file.pdf', $array['url']);
        $this->assertEquals('Example File', $array['name']);
        $this->assertEquals(
            ['url' => 'https://example.com/file.pdf', 'name' => 'Example File'],
            $array
        );
    }
}
