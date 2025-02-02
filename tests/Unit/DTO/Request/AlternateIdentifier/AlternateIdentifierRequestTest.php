<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Request\AlternateIdentifier;

use Apiera\Sdk\DTO\Request\AlternateIdentifier\AlternateIdentifierRequest;
use Apiera\Sdk\Interface\DTO\RequestInterface;
use PHPUnit\Framework\TestCase;

final class AlternateIdentifierRequestTest extends TestCase
{
    public function testInstanceOf(): void
    {
        $request = new AlternateIdentifierRequest('', '');

        $this->assertInstanceOf(AlternateIdentifierRequest::class, $request);
        $this->assertInstanceOf(RequestInterface::class, $request);
    }

    public function testConstructorAndGetters(): void
    {
        $request = new AlternateIdentifierRequest(
            code: 'ABC123',
            type: 'gtin',
            iri: '/api/v1/alternate_identifiers/321'
        );

        $this->assertEquals('ABC123', $request->getCode());
        $this->assertEquals('gtin', $request->getType());
        $this->assertEquals('/api/v1/alternate_identifiers/321', $request->getIri());
    }

    public function testConstructorWithMinimalParameters(): void
    {
        $request = new AlternateIdentifierRequest('ABC123', 'gtin');

        $this->assertEquals('ABC123', $request->getCode());
        $this->assertEquals('gtin', $request->getType());
        $this->assertNull($request->getIri());
    }

    public function testToArray(): void
    {
        $request = new AlternateIdentifierRequest(
            code: 'ABC123',
            type: 'gtin',
            iri: '/api/v1/alternate_identifiers/321'
        );

        $array = $request->toArray();

        $this->assertArrayHasKey('code', $array);
        $this->assertArrayHasKey('type', $array);
        $this->assertArrayNotHasKey('iri', $array);

        $this->assertEquals([
            'code' => 'ABC123',
            'type' => 'gtin',
        ], $array);
    }
}
