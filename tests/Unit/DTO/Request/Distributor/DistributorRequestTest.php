<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Request\Distributor;

use Apiera\Sdk\DTO\Request\Distributor\DistributorRequest;
use Apiera\Sdk\Interface\DTO\RequestInterface;
use PHPUnit\Framework\TestCase;

final class DistributorRequestTest extends TestCase
{
    public function testInstanceOf(): void
    {
        $request = new DistributorRequest('', '');

        $this->assertInstanceOf(DistributorRequest::class, $request);
        $this->assertInstanceOf(RequestInterface::class, $request);
    }

    public function testConstructorAndGetters(): void
    {
        $request = new DistributorRequest(
            name: 'Example Distributor',
            store: '/api/v1/store/123',
            iri: '/api/v1/stores/123/distributors/321'
        );

        $this->assertEquals('Example Distributor', $request->getName());
        $this->assertEquals('/api/v1/store/123', $request->getStore());
        $this->assertEquals('/api/v1/stores/123/distributors/321', $request->getIri());
    }

    public function testConstructorWithMinimalParameters(): void
    {
        $request = new DistributorRequest(name: 'Example Distributor');

        $this->assertEquals('Example Distributor', $request->getName());
        $this->assertNull($request->getStore());
        $this->assertNull($request->getIri());
    }

    public function testToArray(): void
    {
        $request = new DistributorRequest(
            name: 'Example Distributor',
            store: '/api/v1/store/123',
            iri: '/api/v1/stores/123/distributors/321'
        );

        $array = $request->toArray();

        $this->assertArrayHasKey('name', $array);
        $this->assertArrayNotHasKey('store', $array);
        $this->assertArrayNotHasKey('iri', $array);

        $this->assertEquals('Example Distributor', $array['name']);
        $this->assertEquals(['name' => 'Example Distributor'], $array);
    }
}
