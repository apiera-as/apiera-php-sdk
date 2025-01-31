<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Request\AttributeTerm;

use Apiera\Sdk\DTO\Request\AttributeTerm\AttributeTermRequest;
use Apiera\Sdk\Interface\DTO\RequestInterface;
use PHPUnit\Framework\TestCase;

final class AttributeTermRequestTest extends TestCase
{
    public function testInstanceOf(): void
    {
        $request = new AttributeTermRequest(name: '');

        $this->assertInstanceOf(AttributeTermRequest::class, $request);
        $this->assertInstanceOf(RequestInterface::class, $request);
    }

    public function testConstructorAndGetters(): void
    {
        $request = new AttributeTermRequest(
            name: '32gb',
            attribute: '/api/v1/stores/123/attributes/321',
            iri: '/api/v1/stores/123/attributes/456/terms/789'
        );

        $this->assertEquals('32gb', $request->getName());
        $this->assertEquals('/api/v1/stores/123/attributes/321', $request->getAttribute());
        $this->assertEquals('/api/v1/stores/123/attributes/456/terms/789', $request->getIri());
    }

    public function testConstructorWithMinimalParameters(): void
    {
        $request = new AttributeTermRequest(name: '32gb');

        $this->assertEquals('32gb', $request->getName());
        $this->assertNull($request->getAttribute());
        $this->assertNull($request->getIri());
    }

    public function testToArray(): void
    {
        $request = new AttributeTermRequest(
            name: '32gb',
            attribute: '/api/v1/stores/123/attributes/321',
            iri: '/api/v1/stores/123/attributes/456/terms/789'
        );

        $array = $request->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('name', $array);
        $this->assertArrayNotHasKey('attribute', $array);
        $this->assertArrayNotHasKey('iri', $array);
        $this->assertEquals(['name' => '32gb'], $array);
    }
}
