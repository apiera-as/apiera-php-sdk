<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Request\Brand;

use Apiera\Sdk\DTO\Request\Brand\BrandRequest;
use Apiera\Sdk\Interface\DTO\RequestInterface;
use PHPUnit\Framework\TestCase;

final class BrandRequestTest extends TestCase
{
    public function testInstanceOf(): void
    {
        $request = new BrandRequest('');

        $this->assertInstanceOf(BrandRequest::class, $request);
        $this->assertInstanceOf(RequestInterface::class, $request);
    }

    public function testConstructorAndGetters(): void
    {
        $request = new BrandRequest(
            name: 'Apiera',
            description: 'SaaS company',
            image: '/api/v1/files/789',
            store: '/api/v1/stores/123',
            iri: '/api/v1/stores/123/brands/321'
        );

        $this->assertEquals('Apiera', $request->getName());
        $this->assertEquals('/api/v1/stores/123', $request->getStore());
        $this->assertEquals('SaaS company', $request->getDescription());
        $this->assertEquals('/api/v1/files/789', $request->getImage());
        $this->assertEquals('/api/v1/stores/123/brands/321', $request->getIri());
    }

    public function testConstructorWithMinimalParameters(): void
    {
        $request = new BrandRequest('Apiera');

        $this->assertEquals('Apiera', $request->getName());
        $this->assertNull($request->getStore());
        $this->assertNull($request->getDescription());
        $this->assertNull($request->getImage());
        $this->assertNull($request->getIri());
    }

    public function testToArray(): void
    {
        $request = new BrandRequest(
            name: 'Apiera',
            description: 'SaaS company',
            image: '/api/v1/files/789',
            store: '/api/v1/stores/123',
            iri: '/api/v1/stores/123/brands/321'
        );

        $array = $request->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('name', $array);
        $this->assertArrayHasKey('description', $array);
        $this->assertArrayHasKey('image', $array);
        $this->assertArrayNotHasKey('store', $array);
        $this->assertArrayNotHasKey('iri', $array);

        $this->assertEquals([
            'name' => 'Apiera',
            'description' => 'SaaS company',
            'image' => '/api/v1/files/789',
        ], $array);
    }

    public function testToArrayWithMinimalParameters(): void
    {
        $request = new BrandRequest('Apiera');

        $array = $request->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('name', $array);
        $this->assertArrayHasKey('description', $array);
        $this->assertArrayHasKey('image', $array);
        $this->assertArrayNotHasKey('store', $array);
        $this->assertArrayNotHasKey('iri', $array);

        $this->assertEquals([
            'name' => 'Apiera',
            'description' => null,
            'image' => null,
        ], $array);
    }
}
