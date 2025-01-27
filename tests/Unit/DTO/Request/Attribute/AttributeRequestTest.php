<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Request\Attribute;

use Apiera\Sdk\DTO\Request\Attribute\AttributeRequest;
use Apiera\Sdk\Interface\DTO\RequestInterface;
use PHPUnit\Framework\TestCase;

final class AttributeRequestTest extends TestCase
{
    public function testInstanceOf(): void
    {
        $request = new AttributeRequest('', '');

        $this->assertInstanceOf(AttributeRequest::class, $request);
        $this->assertInstanceOf(RequestInterface::class, $request);
    }

    public function testConstructorAndGetters(): void
    {
        $request = new AttributeRequest(
            name: 'Color',
            store: '/api/v1/store/123',
            iri: '/api/v1/stores/123/attribute/321'
        );

        $this->assertEquals('Color', $request->getName());
        $this->assertEquals('/api/v1/store/123', $request->getStore());
        $this->assertEquals('/api/v1/stores/123/attribute/321', $request->getIri());
    }

    public function testConstructorWithMinimalParameters(): void
    {
        $request = new AttributeRequest('Color');

        $this->assertEquals('Color', $request->getName());
        $this->assertNull($request->getStore());
        $this->assertNull($request->getIri());
    }

    public function testToArray(): void
    {
        $request = new AttributeRequest(
            name: 'Color',
            store: '/api/v1/store/123',
            iri: '/api/v1/stores/123/attribute/321'
        );

        $array = $request->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('name', $array);
        $this->assertArrayNotHasKey('store', $array);
        $this->assertArrayNotHasKey('iri', $array);

        $this->assertEquals('Color', $array['name']);
        $this->assertEquals(['name' => 'Color'], $array);
    }
}
