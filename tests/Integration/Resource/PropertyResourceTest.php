<?php

declare(strict_types=1);

namespace Tests\Integration\Resource;

use Apiera\Sdk\ApieraSdk;
use Apiera\Sdk\Configuration;
use Apiera\Sdk\DTO\QueryParameters;
use Apiera\Sdk\DTO\Request\Property\PropertyRequest;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;

final class PropertyResourceTest extends TestCase
{
    private MockHandler $mockHandler;

    /** @var array<int, array<string, mixed>>|\ArrayAccess<int, array<string, mixed>> */
    private array|\ArrayAccess $requestHistory = [];
    private ApieraSdk $sdk;

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function testFindPropertiesFlow(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'access_token' => 'test_token',
            'expires_in' => 3600,
        ])));

        $storeId = '9d024f41-3faf-4eef-9d2d-a7e506b81afb';
        $propertyId = 'e548e809-2ab1-4832-8dd9-f67115da61fb';
        $baseUrl = 'https://api.test/api/v1';

        $propertyData = [
            '@context' => '/api/v1/contexts/Property',
            '@id' => '/api/v1/stores/123/properties',
            '@type' => 'Collection',
            'member' => [[
                '@id' => sprintf('%s/stores/%s/properties/%s', $baseUrl, $storeId, $propertyId),
                '@type' => 'Property',
                'uuid' => $propertyId,
                'createdAt' => '2024-12-17T09:18:32+00:00',
                'updatedAt' => '2024-12-17T09:18:32+00:00',
                'name' => 'Test property',
                'store' => sprintf('%s/stores/%s', $baseUrl, $storeId),
            ]],
            'totalItems' => 1,
        ];
        $this->mockHandler->append(new Response(200, [], json_encode($propertyData)));

        $request = new PropertyRequest(
            name: 'Test property',
            store: sprintf('/api/v1/stores/%s', $storeId)
        );

        $response = $this->sdk->property()->find($request);

        $this->assertCount(2, $this->requestHistory);

        $authRequest = $this->requestHistory[0]['request'];
        $this->assertEquals('POST', $authRequest->getMethod());
        $this->assertEquals('https://auth.test/oauth/token', (string)$authRequest->getUri());

        $propertyRequest = $this->requestHistory[1]['request'];
        $this->assertEquals('GET', $propertyRequest->getMethod());
        $this->assertEquals('Bearer test_token', $propertyRequest->getHeader('Authorization')[0]);
        $this->assertEquals('application/ld+json', $propertyRequest->getHeader('Content-Type')[0]);
        $this->assertEquals(
            sprintf('%s/stores/%s/properties', $baseUrl, $storeId),
            (string)$propertyRequest->getUri()
        );

        $this->assertEquals(1, $response->getTotalItems());
        $this->assertCount(1, $response->getMembers());
        $this->assertEquals('Test property', $response->getMembers()[0]->getName());
        $this->assertEquals($propertyId, $response->getMembers()[0]->getUuid()->toString());
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function testFindOneByPropertyFlow(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'access_token' => 'test_token',
            'expires_in' => 3600,
        ])));

        $storeId = '9d024f41-3faf-4eef-9d2d-a7e506b81afb';
        $propertyId = 'e548e809-2ab1-4832-8dd9-f67115da61fb';
        $baseUrl = 'https://api.test/api/v1';

        $propertyData = [
            '@context' => '/api/v1/contexts/Property',
            '@id' => '/api/v1/stores/123/properties',
            '@type' => 'Collection',
            'member' => [[
                '@id' => sprintf('%s/stores/%s/properties/%s', $baseUrl, $storeId, $propertyId),
                '@type' => 'Property',
                'uuid' => $propertyId,
                'createdAt' => '2024-12-17T09:18:32+00:00',
                'updatedAt' => '2024-12-17T09:18:32+00:00',
                'name' => 'Test property',
                'store' => sprintf('%s/stores/%s', $baseUrl, $storeId),
            ]],
            'totalItems' => 1,
        ];
        $this->mockHandler->append(new Response(200, [], json_encode($propertyData)));

        $request = new PropertyRequest(
            name: 'Test property',
            store: sprintf('api/v1/stores/%s', $storeId),
        );

        $params = new QueryParameters(filters: ['name' => 'Test property']);
        $response = $this->sdk->property()->findOneBy($request, $params);

        $this->assertCount(2, $this->requestHistory);

        $authRequest = $this->requestHistory[0]['request'];
        $this->assertEquals('POST', $authRequest->getMethod());
        $this->assertEquals('https://auth.test/oauth/token', (string)$authRequest->getUri());

        $propertyRequest = $this->requestHistory[1]['request'];
        $this->assertEquals('GET', $propertyRequest->getMethod());
        $this->assertEquals('Bearer test_token', $propertyRequest->getHeader('Authorization')[0]);
        $this->assertEquals('application/ld+json', $propertyRequest->getHeader('Content-Type')[0]);
        $this->assertEquals(
            sprintf(
                '%s/stores/%s/properties?filters%%5Bname%%5D=%s',
                $baseUrl,
                $storeId,
                rawurlencode('Test property')
            ),
            (string)$propertyRequest->getUri()
        );

        $this->assertEquals('Test property', $response->getName());
        $this->assertEquals($propertyId, $response->getUuid()->toString());
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function testCreatePropertyFlow(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'access_token' => 'test_token',
            'expires_in' => 3600,
        ])));

        $storeId = '9d024f41-3faf-4eef-9d2d-a7e506b81afb';
        $propertyId = 'e548e809-2ab1-4832-8dd9-f67115da61fb';
        $baseUrl = 'https://api.test/api/v1';

        $propertyData = [
            '@id' => sprintf('%s/stores/%s/properties/%s', $baseUrl, $storeId, $propertyId),
            '@type' => 'Property',
            'uuid' => $propertyId,
            'createdAt' => '2024-12-17T09:18:32+00:00',
            'updatedAt' => '2024-12-17T09:18:32+00:00',
            'name' => 'Test property',
            'store' => sprintf('%s/stores/%s', $baseUrl, $storeId),
        ];
        $this->mockHandler->append(new Response(201, [], json_encode($propertyData)));

        $request = new PropertyRequest(
            name: 'Test property',
            store: sprintf('api/v1/stores/%s', $storeId),
        );

        $response = $this->sdk->property()->create($request);

        $this->assertCount(2, $this->requestHistory);

        $authRequest = $this->requestHistory[0]['request'];
        $this->assertEquals('POST', $authRequest->getMethod());
        $this->assertEquals('https://auth.test/oauth/token', (string)$authRequest->getUri());

        $propertyRequest = $this->requestHistory[1]['request'];
        $this->assertEquals('POST', $propertyRequest->getMethod());
        $this->assertEquals('Bearer test_token', $propertyRequest->getHeader('Authorization')[0]);
        $this->assertEquals('application/ld+json', $propertyRequest->getHeader('Content-Type')[0]);
        $this->assertEquals(
            sprintf('%s/stores/%s/properties', $baseUrl, $storeId),
            (string)$propertyRequest->getUri()
        );

        $this->assertEquals('Test property', $response->getName());
        $this->assertEquals($propertyId, $response->getUuid()->toString());
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function testUpdatePropertyFlow(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'access_token' => 'test_token',
            'expires_in' => 3600,
        ])));

        $storeId = '9d024f41-3faf-4eef-9d2d-a7e506b81afb';
        $propertyId = 'e548e809-2ab1-4832-8dd9-f67115da61fb';
        $baseUrl = 'https://api.test/api/v1';

        $propertyData = [
            '@id' => sprintf('%s/stores/%s/properties/%s', $baseUrl, $storeId, $propertyId),
            '@type' => 'Property',
            'uuid' => $propertyId,
            'createdAt' => '2024-12-17T09:18:32+00:00',
            'updatedAt' => '2024-12-17T09:18:32+00:00',
            'name' => 'Updated property',
            'store' => sprintf('%s/stores/%s', $baseUrl, $storeId),
        ];
        $this->mockHandler->append(new Response(200, [], json_encode($propertyData)));

        $request = new PropertyRequest(
            name: 'Updated property',
            iri: sprintf('%s/stores/%s/properties/%s', $baseUrl, $storeId, $propertyId)
        );

        $response = $this->sdk->property()->update($request);

        $this->assertCount(2, $this->requestHistory);

        $authRequest = $this->requestHistory[0]['request'];
        $this->assertEquals('POST', $authRequest->getMethod());
        $this->assertEquals('https://auth.test/oauth/token', (string)$authRequest->getUri());

        $propertyRequest = $this->requestHistory[1]['request'];
        $this->assertEquals('PATCH', $propertyRequest->getMethod());
        $this->assertEquals('Bearer test_token', $propertyRequest->getHeader('Authorization')[0]);
        $this->assertEquals('application/merge-patch+json', $propertyRequest->getHeader('Content-Type')[0]);
        $this->assertEquals(
            sprintf(
                '%s/stores/%s/properties/%s',
                $baseUrl,
                $storeId,
                $propertyId
            ),
            (string)$propertyRequest->getUri()
        );

        $this->assertEquals('Updated property', $response->getName());
        $this->assertEquals($propertyId, $response->getUuid()->toString());
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function testDeletePropertyFlow(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'access_token' => 'test_token',
            'expires_in' => 3600,
        ])));

        $this->mockHandler->append(new Response(204));

        $storeId = '9d024f41-3faf-4eef-9d2d-a7e506b81afb';
        $propertyId = 'e548e809-2ab1-4832-8dd9-f67115da61fb';
        $baseUrl = 'https://api.test/api/v1';

        $request = new PropertyRequest(
            name: 'Test property',
            iri: sprintf('%s/stores/%s/properties/%s', $baseUrl, $storeId, $propertyId)
        );

        $this->sdk->property()->delete($request);

        $this->assertCount(2, $this->requestHistory);

        $authRequest = $this->requestHistory[0]['request'];
        $this->assertEquals('POST', $authRequest->getMethod());
        $this->assertEquals('https://auth.test/oauth/token', (string)$authRequest->getUri());

        $propertyRequest = $this->requestHistory[1]['request'];
        $this->assertEquals('DELETE', $propertyRequest->getMethod());
        $this->assertEquals('Bearer test_token', $propertyRequest->getHeader('Authorization')[0]);
        $this->assertEquals(
            sprintf('%s/stores/%s/properties/%s', $baseUrl, $storeId, $propertyId),
            (string)$propertyRequest->getUri()
        );
    }

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     * @throws \Apiera\Sdk\Exception\ConfigurationException
     */
    protected function setUp(): void
    {
        $this->mockHandler = new MockHandler();
        $handlerStack = HandlerStack::create($this->mockHandler);

        $history = Middleware::history($this->requestHistory);
        $handlerStack->push($history);

        $cacheItemMock = $this->createMock(CacheItemInterface::class);
        $cacheItemMock->method('isHit')->willReturn(false);
        $cacheItemMock->method('get')->willReturn(null);
        $cacheItemMock->method('set')->willReturnSelf();
        $cacheItemMock->method('expiresAfter')->willReturnSelf();

        $cacheMock = $this->createMock(CacheItemPoolInterface::class);
        $cacheMock->method('getItem')->willReturn($cacheItemMock);
        $cacheMock->method('save')->willReturn(true);

        $config = new Configuration(
            baseUrl: 'https://api.test',
            userAgent: 'Test/1.0',
            oauthDomain: 'auth.test',
            oauthClientId: 'test_client',
            oauthClientSecret: 'test_secret',
            oauthCookieSecret: 'test_cookie',
            oauthAudience: 'test_audience',
            oauthOrganizationId: 'test_org',
            cache: $cacheMock,
            timeout: 10,
            debugMode: false,
            options: [
                'handler' => $handlerStack,
            ]
        );

        $this->sdk = new ApieraSdk($config);
    }
}
