<?php

namespace Tests\Integration\Resource;

use Apiera\Sdk\ApieraSdk;
use Apiera\Sdk\Configuration;
use Apiera\Sdk\DTO\QueryParameters;
use Apiera\Sdk\DTO\Request\Attribute\AttributeRequest;
use Apiera\Sdk\Exception\ClientException;
use Apiera\Sdk\Exception\InvalidRequestException;
use Apiera\Sdk\Interface\ClientExceptionInterface;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;

class AttributeResourceTest extends TestCase
{
    private MockHandler $mockHandler;
    private array $requestHistory = [];
    private ApieraSdk $sdk;

    /**
     * @throws Exception
     * @throws ClientException
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
                'handler' => $handlerStack
            ]
        );

        $this->sdk = new ApieraSdk($config);
    }

    /**
     * @throws ClientExceptionInterface
     * @throws InvalidRequestException
     */
    public function testFindAttributesFlow(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'access_token' => 'test_token',
            'expires_in' => 3600
        ])));

        $attributeData = [
            '@context' => '/api/contexts/Attribute',
            '@id' => '/api/attributes',
            '@type' => 'Collection',
            'member' => [[
                '@id' => 'https://api.test/api/v1/stores/9d024f41-3faf-4eef-9d2d-a7e506b81afb/attributes/e548e809-2ab1-4832-8dd9-f67115da61fb',
                '@type' => 'Attribute',
                'uuid' => 'e548e809-2ab1-4832-8dd9-f67115da61fb',
                'createdAt' => '2024-12-17T09:18:32+00:00',
                'updatedAt' => '2024-12-17T09:18:32+00:00',
                'name' => 'Test attribute',
                'store' => 'https://api.test/api/v1/stores/9d024f41-3faf-4eef-9d2d-a7e506b81afb'
            ]],
            'totalItems' => 1
        ];
        $this->mockHandler->append(new Response(200, [], json_encode($attributeData)));

        $request = new AttributeRequest(
            name: 'Test attribute',
            store: '/api/v1/stores/9d024f41-3faf-4eef-9d2d-a7e506b81afb'
        );

        $response = $this->sdk->attribute()->find($request);

        $this->assertCount(2, $this->requestHistory);

        $authRequest = $this->requestHistory[0]['request'];
        $this->assertEquals('POST', $authRequest->getMethod());
        $this->assertEquals('https://auth.test/oauth/token', (string)$authRequest->getUri());

        $attributeRequest = $this->requestHistory[1]['request'];
        $this->assertEquals('GET', $attributeRequest->getMethod());
        $this->assertEquals('Bearer test_token', $attributeRequest->getHeader('Authorization')[0]);
        $this->assertEquals('application/ld+json', $attributeRequest->getHeader('Content-Type')[0]);
        $this->assertEquals(
            'https://api.test/api/v1/stores/9d024f41-3faf-4eef-9d2d-a7e506b81afb/attributes',
            (string)$attributeRequest->getUri()
        );

        $this->assertEquals(1, $response->getTotalItems());
        $this->assertCount(1, $response->getMembers());
        $this->assertEquals('Test attribute', $response->getMembers()[0]->getName());
        $this->assertEquals('e548e809-2ab1-4832-8dd9-f67115da61fb', $response->getMembers()[0]->getUuid()->toString());
    }

    /**
     * @throws ClientExceptionInterface
     * @throws InvalidRequestException
     */
    public function testFindOneByAttributeFlow(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'access_token' => 'test_token',
            'expires_in' => 3600
        ])));

        $attributeData = [
            '@context' => '/api/contexts/Attribute',
            '@id' => '/api/attributes',
            '@type' => 'Collection',
            'member' => [[
                '@id' => 'https://api.test/api/v1/stores/9d024f41-3faf-4eef-9d2d-a7e506b81afb/attributes/e548e809-2ab1-4832-8dd9-f67115da61fb',
                '@type' => 'Attribute',
                'uuid' => 'e548e809-2ab1-4832-8dd9-f67115da61fb',
                'createdAt' => '2024-12-17T09:18:32+00:00',
                'updatedAt' => '2024-12-17T09:18:32+00:00',
                'name' => 'Test attribute',
                'store' => 'https://api.test/api/v1/stores/9d024f41-3faf-4eef-9d2d-a7e506b81afb'
            ]],
            'totalItems' => 1
        ];
        $this->mockHandler->append(new Response(200, [], json_encode($attributeData)));

        $request = new AttributeRequest(
            name: 'Test attribute',
            store: '/api/v1/stores/9d024f41-3faf-4eef-9d2d-a7e506b81afb'
        );

        $params = new QueryParameters(filters: ['name' => 'Test attribute']);
        $response = $this->sdk->attribute()->findOneBy($request, $params);

        $this->assertCount(2, $this->requestHistory);

        $authRequest = $this->requestHistory[0]['request'];
        $this->assertEquals('POST', $authRequest->getMethod());
        $this->assertEquals('https://auth.test/oauth/token', (string)$authRequest->getUri());

        $attributeRequest = $this->requestHistory[1]['request'];
        $this->assertEquals('GET', $attributeRequest->getMethod());
        $this->assertEquals('Bearer test_token', $attributeRequest->getHeader('Authorization')[0]);
        $this->assertEquals('application/ld+json', $attributeRequest->getHeader('Content-Type')[0]);
        $this->assertEquals(
            'https://api.test/api/v1/stores/9d024f41-3faf-4eef-9d2d-a7e506b81afb/attributes?filters%5Bname%5D=Test%20attribute',
            (string)$attributeRequest->getUri()
        );

        $this->assertEquals('Test attribute', $response->getName());
        $this->assertEquals('e548e809-2ab1-4832-8dd9-f67115da61fb', $response->getUuid()->toString());
    }

    /**
     * @throws ClientExceptionInterface
     * @throws InvalidRequestException
     */
    public function testCreateAttributeFlow(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'access_token' => 'test_token',
            'expires_in' => 3600
        ])));

        $attributeData = [
            '@id' => 'https://api.test/api/v1/stores/9d024f41-3faf-4eef-9d2d-a7e506b81afb/attributes/e548e809-2ab1-4832-8dd9-f67115da61fb',
            '@type' => 'Attribute',
            'uuid' => 'e548e809-2ab1-4832-8dd9-f67115da61fb',
            'createdAt' => '2024-12-17T09:18:32+00:00',
            'updatedAt' => '2024-12-17T09:18:32+00:00',
            'name' => 'Test attribute',
            'store' => 'https://api.test/api/v1/stores/9d024f41-3faf-4eef-9d2d-a7e506b81afb'
        ];
        $this->mockHandler->append(new Response(201, [], json_encode($attributeData)));

        $request = new AttributeRequest(
            name: 'Test attribute',
            store: '/api/v1/stores/9d024f41-3faf-4eef-9d2d-a7e506b81afb'
        );

        $response = $this->sdk->attribute()->create($request);

        $this->assertCount(2, $this->requestHistory);

        $authRequest = $this->requestHistory[0]['request'];
        $this->assertEquals('POST', $authRequest->getMethod());
        $this->assertEquals('https://auth.test/oauth/token', (string)$authRequest->getUri());

        $attributeRequest = $this->requestHistory[1]['request'];
        $this->assertEquals('POST', $attributeRequest->getMethod());
        $this->assertEquals('Bearer test_token', $attributeRequest->getHeader('Authorization')[0]);
        $this->assertEquals('application/ld+json', $attributeRequest->getHeader('Content-Type')[0]);
        $this->assertEquals(
            'https://api.test/api/v1/stores/9d024f41-3faf-4eef-9d2d-a7e506b81afb/attributes',
            (string)$attributeRequest->getUri()
        );

        $this->assertEquals('Test attribute', $response->getName());
        $this->assertEquals('e548e809-2ab1-4832-8dd9-f67115da61fb', $response->getUuid()->toString());
    }

    /**
     * @throws ClientExceptionInterface
     * @throws InvalidRequestException
     */
    public function testUpdateAttributeFlow(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'access_token' => 'test_token',
            'expires_in' => 3600
        ])));

        $attributeData = [
            '@id' => 'https://api.test/api/v1/stores/9d024f41-3faf-4eef-9d2d-a7e506b81afb/attributes/e548e809-2ab1-4832-8dd9-f67115da61fb',
            '@type' => 'Attribute',
            'uuid' => 'e548e809-2ab1-4832-8dd9-f67115da61fb',
            'createdAt' => '2024-12-17T09:18:32+00:00',
            'updatedAt' => '2024-12-17T09:18:32+00:00',
            'name' => 'Updated attribute',
            'store' => 'https://api.test/api/v1/stores/9d024f41-3faf-4eef-9d2d-a7e506b81afb'
        ];
        $this->mockHandler->append(new Response(200, [], json_encode($attributeData)));

        $request = new AttributeRequest(
            name: 'Updated attribute',
            iri: 'https://api.test/api/v1/stores/9d024f41-3faf-4eef-9d2d-a7e506b81afb/attributes/e548e809-2ab1-4832-8dd9-f67115da61fb'
        );

        $response = $this->sdk->attribute()->update($request);

        $this->assertCount(2, $this->requestHistory);

        $authRequest = $this->requestHistory[0]['request'];
        $this->assertEquals('POST', $authRequest->getMethod());
        $this->assertEquals('https://auth.test/oauth/token', (string)$authRequest->getUri());

        $attributeRequest = $this->requestHistory[1]['request'];
        $this->assertEquals('PATCH', $attributeRequest->getMethod());
        $this->assertEquals('Bearer test_token', $attributeRequest->getHeader('Authorization')[0]);
        $this->assertEquals('application/merge-patch+json', $attributeRequest->getHeader('Content-Type')[0]);
        $this->assertEquals(
            'https://api.test/api/v1/stores/9d024f41-3faf-4eef-9d2d-a7e506b81afb/attributes/e548e809-2ab1-4832-8dd9-f67115da61fb',
            (string)$attributeRequest->getUri()
        );

        $this->assertEquals('Updated attribute', $response->getName());
        $this->assertEquals('e548e809-2ab1-4832-8dd9-f67115da61fb', $response->getUuid()->toString());
    }

    /**
     * @throws ClientExceptionInterface
     * @throws InvalidRequestException
     */
    public function testDeleteAttributeFlow(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'access_token' => 'test_token',
            'expires_in' => 3600
        ])));

        $this->mockHandler->append(new Response(204));

        $request = new AttributeRequest(
            name: 'Test attribute',
            iri: 'https://api.test/api/v1/stores/9d024f41-3faf-4eef-9d2d-a7e506b81afb/attributes/e548e809-2ab1-4832-8dd9-f67115da61fb'
        );

        $this->sdk->attribute()->delete($request);

        $this->assertCount(2, $this->requestHistory);

        // Verify auth request
        $authRequest = $this->requestHistory[0]['request'];
        $this->assertEquals('POST', $authRequest->getMethod());
        $this->assertEquals('https://auth.test/oauth/token', (string)$authRequest->getUri());

        // Verify attribute delete request
        $attributeRequest = $this->requestHistory[1]['request'];
        $this->assertEquals('DELETE', $attributeRequest->getMethod());
        $this->assertEquals('Bearer test_token', $attributeRequest->getHeader('Authorization')[0]);
        $this->assertEquals(
            'https://api.test/api/v1/stores/9d024f41-3faf-4eef-9d2d-a7e506b81afb/attributes/e548e809-2ab1-4832-8dd9-f67115da61fb',
            (string)$attributeRequest->getUri()
        );
    }
}
