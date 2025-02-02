<?php

declare(strict_types=1);

namespace Tests\Integration\Resource;

use Apiera\Sdk\ApieraSdk;
use Apiera\Sdk\Configuration;
use Apiera\Sdk\DTO\QueryParameters;
use Apiera\Sdk\DTO\Request\Attribute\AttributeRequest;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;

final class AttributeResourceTest extends TestCase
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
    public function testFindAttributesFlow(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'access_token' => 'test_token',
            'expires_in' => 3600,
        ])));

        $storeId = '9d024f41-3faf-4eef-9d2d-a7e506b81afb';
        $attributeId = 'e548e809-2ab1-4832-8dd9-f67115da61fb';
        $baseUrl = 'https://api.test/api/v1';

        $attributeData = [
            '@context' => '/api/contexts/Attribute',
            '@id' => '/api/attributes',
            '@type' => 'Collection',
            'member' => [[
                '@id' => sprintf('%s/stores/%s/attributes/%s', $baseUrl, $storeId, $attributeId),
                '@type' => 'Attribute',
                'uuid' => $attributeId,
                'createdAt' => '2024-12-17T09:18:32+00:00',
                'updatedAt' => '2024-12-17T09:18:32+00:00',
                'name' => 'Test attribute',
                'store' => sprintf('%s/stores/%s', $baseUrl, $storeId),
            ]],
            'totalItems' => 1,
        ];
        $this->mockHandler->append(new Response(200, [], json_encode($attributeData)));

        $request = new AttributeRequest(
            name: 'Test attribute',
            store: sprintf('/api/v1/stores/%s', $storeId)
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
            sprintf('%s/stores/%s/attributes', $baseUrl, $storeId),
            (string)$attributeRequest->getUri()
        );

        $this->assertEquals(1, $response->getTotalItems());
        $this->assertCount(1, $response->getMembers());
        $this->assertEquals('Test attribute', $response->getMembers()[0]->getName());
        $this->assertEquals($attributeId, $response->getMembers()[0]->getUuid()->toString());
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function testFindOneByAttributeFlow(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'access_token' => 'test_token',
            'expires_in' => 3600,
        ])));

        $storeId = '9d024f41-3faf-4eef-9d2d-a7e506b81afb';
        $attributeId = 'e548e809-2ab1-4832-8dd9-f67115da61fb';
        $baseUrl = 'https://api.test/api/v1';

        $attributeData = [
            '@context' => '/api/contexts/Attribute',
            '@id' => '/api/attributes',
            '@type' => 'Collection',
            'member' => [[
                '@id' => sprintf('%s/stores/%s/attributes/%s', $baseUrl, $storeId, $attributeId),
                '@type' => 'Attribute',
                'uuid' => $attributeId,
                'createdAt' => '2024-12-17T09:18:32+00:00',
                'updatedAt' => '2024-12-17T09:18:32+00:00',
                'name' => 'Test attribute',
                'store' => sprintf('%s/stores/%s', $baseUrl, $storeId),
            ]],
            'totalItems' => 1,
        ];
        $this->mockHandler->append(new Response(200, [], json_encode($attributeData)));

        $request = new AttributeRequest(
            name: 'Test attribute',
            store: sprintf('api/v1/stores/%s', $storeId),
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
            sprintf(
                '%s/stores/%s/attributes?filters%%5Bname%%5D=%s',
                $baseUrl,
                $storeId,
                rawurlencode('Test attribute')
            ),
            (string)$attributeRequest->getUri()
        );

        $this->assertEquals('Test attribute', $response->getName());
        $this->assertEquals($attributeId, $response->getUuid()->toString());
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function testCreateAttributeFlow(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'access_token' => 'test_token',
            'expires_in' => 3600,
        ])));

        $storeId = '9d024f41-3faf-4eef-9d2d-a7e506b81afb';
        $attributeId = 'e548e809-2ab1-4832-8dd9-f67115da61fb';
        $baseUrl = 'https://api.test/api/v1';

        $attributeData = [
            '@id' => sprintf('%s/stores/%s/attributes/%s', $baseUrl, $storeId, $attributeId),
            '@type' => 'Attribute',
            'uuid' => $attributeId,
            'createdAt' => '2024-12-17T09:18:32+00:00',
            'updatedAt' => '2024-12-17T09:18:32+00:00',
            'name' => 'Test attribute',
            'store' => sprintf('%s/stores/%s', $baseUrl, $storeId),
        ];
        $this->mockHandler->append(new Response(201, [], json_encode($attributeData)));

        $request = new AttributeRequest(
            name: 'Test attribute',
            store: sprintf('api/v1/stores/%s', $storeId),
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
            sprintf('%s/stores/%s/attributes', $baseUrl, $storeId),
            (string)$attributeRequest->getUri()
        );

        $this->assertEquals('Test attribute', $response->getName());
        $this->assertEquals($attributeId, $response->getUuid()->toString());
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function testUpdateAttributeFlow(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'access_token' => 'test_token',
            'expires_in' => 3600,
        ])));

        $storeId = '9d024f41-3faf-4eef-9d2d-a7e506b81afb';
        $attributeId = 'e548e809-2ab1-4832-8dd9-f67115da61fb';
        $baseUrl = 'https://api.test/api/v1';

        $attributeData = [
            '@id' => sprintf('%s/stores/%s/attributes/%s', $baseUrl, $storeId, $attributeId),
            '@type' => 'Attribute',
            'uuid' => $attributeId,
            'createdAt' => '2024-12-17T09:18:32+00:00',
            'updatedAt' => '2024-12-17T09:18:32+00:00',
            'name' => 'Updated attribute',
            'store' => sprintf('%s/stores/%s', $baseUrl, $storeId),
        ];
        $this->mockHandler->append(new Response(200, [], json_encode($attributeData)));

        $request = new AttributeRequest(
            name: 'Updated attribute',
            iri: sprintf('%s/stores/%s/attributes/%s', $baseUrl, $storeId, $attributeId)
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
            sprintf(
                '%s/stores/%s/attributes/%s',
                $baseUrl,
                $storeId,
                $attributeId
            ),
            (string)$attributeRequest->getUri()
        );

        $this->assertEquals('Updated attribute', $response->getName());
        $this->assertEquals($attributeId, $response->getUuid()->toString());
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function testDeleteAttributeFlow(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'access_token' => 'test_token',
            'expires_in' => 3600,
        ])));

        $storeId = '9d024f41-3faf-4eef-9d2d-a7e506b81afb';
        $attributeId = 'e548e809-2ab1-4832-8dd9-f67115da61fb';
        $baseUrl = 'https://api.test/api/v1';

        $this->mockHandler->append(new Response(204));

        $request = new AttributeRequest(
            name: 'Test attribute',
            iri: sprintf('%s/stores/%s/attributes/%s', $baseUrl, $storeId, $attributeId)
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
            sprintf('%s/stores/%s/attributes/%s', $baseUrl, $storeId, $attributeId),
            (string)$attributeRequest->getUri()
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
