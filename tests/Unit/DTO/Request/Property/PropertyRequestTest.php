<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Request\Property;

use Apiera\Sdk\DTO\Request\Property\PropertyRequest;
use Apiera\Sdk\Interface\DTO\RequestInterface;
use PHPUnit\Framework\TestCase;

final class PropertyRequestTest extends TestCase
{
    public function testInstanceOf(): void
    {
        $request = new PropertyRequest('');

        $this->assertInstanceOf(PropertyRequest::class, $request);
        $this->assertInstanceOf(RequestInterface::class, $request);
    }

    public function testConstructorAndGetters(): void
    {
        $request = new PropertyRequest(
            name: 'Example Property',
            store: '/api/v1/store/123',
            iri: '/api/v1/stores/123/properties/321'
        );

        $this->assertEquals('Example Property', $request->getName());
        $this->assertEquals('/api/v1/store/123', $request->getStore());
        $this->assertEquals('/api/v1/stores/123/properties/321', $request->getIri());
    }

    public function testConstructorWithMinimalParameters(): void
    {
        $request = new PropertyRequest(name: 'Example Property');

        $this->assertEquals('Example Property', $request->getName());
        $this->assertNull($request->getStore());
        $this->assertNull($request->getIri());
    }

    public function testToArray(): void
    {
        $request = new PropertyRequest(
            name: 'Example Property',
            store: '/api/v1/store/123',
            iri: '/api/v1/stores/123/properties/321'
        );

        $array = $request->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('name', $array);
        $this->assertArrayNotHasKey('store', $array);
        $this->assertArrayNotHasKey('iri', $array);

        $this->assertEquals('Example Property', $array['name']);
        $this->assertEquals(['name' => 'Example Property'], $array);
    }
}
