<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Request\Category;

use Apiera\Sdk\DTO\Request\Category\CategoryRequest;
use Apiera\Sdk\Interface\DTO\RequestInterface;
use PHPUnit\Framework\TestCase;

final class CategoryRequestTest extends TestCase
{
    public function testInstanceOf(): void
    {
        $request = new CategoryRequest('');

        $this->assertInstanceOf(CategoryRequest::class, $request);
        $this->assertInstanceOf(RequestInterface::class, $request);
    }

    public function testConstructorAndGetters(): void
    {
        $request = new CategoryRequest(
            name: 'Electronics',
            store: '/api/v1/stores/123',
            description: 'Electronic products',
            parent: '/api/v1/stores/123/categories/456',
            image: '/api/v1/files/789',
            iri: '/api/v1/stores/123/categories/321'
        );

        $this->assertEquals('Electronics', $request->getName());
        $this->assertEquals('/api/v1/stores/123', $request->getStore());
        $this->assertEquals('Electronic products', $request->getDescription());
        $this->assertEquals('/api/v1/stores/123/categories/456', $request->getParent());
        $this->assertEquals('/api/v1/files/789', $request->getImage());
        $this->assertEquals('/api/v1/stores/123/categories/321', $request->getIri());
    }

    public function testConstructorWithMinimalParameters(): void
    {
        $request = new CategoryRequest('Electronics');

        $this->assertEquals('Electronics', $request->getName());
        $this->assertNull($request->getStore());
        $this->assertNull($request->getDescription());
        $this->assertNull($request->getParent());
        $this->assertNull($request->getImage());
        $this->assertNull($request->getIri());
    }

    public function testToArray(): void
    {
        $request = new CategoryRequest(
            name: 'Electronics',
            store: '/api/v1/stores/123',
            description: 'Electronic products',
            parent: '/api/v1/stores/123/categories/456',
            image: '/api/v1/files/789',
            iri: '/api/v1/stores/123/categories/321'
        );

        $array = $request->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('name', $array);
        $this->assertArrayHasKey('description', $array);
        $this->assertArrayHasKey('parent', $array);
        $this->assertArrayHasKey('image', $array);
        $this->assertArrayNotHasKey('store', $array);
        $this->assertArrayNotHasKey('iri', $array);

        $this->assertEquals([
            'name' => 'Electronics',
            'description' => 'Electronic products',
            'parent' => '/api/v1/stores/123/categories/456',
            'image' => '/api/v1/files/789',
        ], $array);
    }

    public function testToArrayWithMinimalParameters(): void
    {
        $request = new CategoryRequest('Electronics');

        $array = $request->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('name', $array);
        $this->assertArrayHasKey('description', $array);
        $this->assertArrayHasKey('parent', $array);
        $this->assertArrayHasKey('image', $array);
        $this->assertArrayNotHasKey('store', $array);
        $this->assertArrayNotHasKey('iri', $array);

        $this->assertEquals([
            'name' => 'Electronics',
            'description' => null,
            'parent' => null,
            'image' => null,
        ], $array);
    }
}
