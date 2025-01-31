<?php

declare(strict_types=1);

namespace Tests\Integration\Resource;

use Apiera\Sdk\ApieraSdk;
use Apiera\Sdk\Configuration;
use Apiera\Sdk\DTO\QueryParameters;
use Apiera\Sdk\DTO\Request\Brand\BrandRequest;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;

final class BrandResourceTest extends TestCase
{
    private MockHandler $mockHandler;

    /** @var array<int, array<string, mixed>>|\ArrayAccess<int, array<string, mixed>> */
    private array|\ArrayAccess $requestHistory = [];
    private ApieraSdk $sdk;

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     */
    public function testFindBrandsFlow(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'access_token' => 'test_token',
            'expires_in' => 3600,
        ])));

        $storeId = '9d024f41-3faf-4eef-9d2d-a7e506b81afb';
        $brandId = 'e548e809-2ab1-4832-8dd9-f67115da61fb';
        $baseUrl = 'https://api.test/api/v1';

        $brandData = [
            '@context' => '/api/contexts/Brand',
            '@id' => '/api/brands',
            '@type' => 'Collection',
            'member' => [[
                '@id' => sprintf('%s/stores/%s/brands/%s', $baseUrl, $storeId, $brandId),
                '@type' => 'Brand',
                'uuid' => $brandId,
                'createdAt' => '2024-12-17T09:18:32+00:00',
                'updatedAt' => '2024-12-17T09:18:32+00:00',
                'name' => 'Test Brand',
                'store' => sprintf('%s/stores/%s', $baseUrl, $storeId),
                'description' => 'Test Brand Description',
                'image' => sprintf('%s/files/%s', $baseUrl, 'abc123'),
            ]],
            'totalItems' => 1,
        ];
        $this->mockHandler->append(new Response(200, [], json_encode($brandData)));

        $request = new BrandRequest(
            name: 'Test Brand',
            store: sprintf('/api/v1/stores/%s', $storeId)
        );

        $response = $this->sdk->brand()->find($request);

        $this->assertCount(2, $this->requestHistory);

        $authRequest = $this->requestHistory[0]['request'];
        $this->assertEquals('POST', $authRequest->getMethod());
        $this->assertEquals('https://auth.test/oauth/token', (string)$authRequest->getUri());

        $brandRequest = $this->requestHistory[1]['request'];
        $this->assertEquals('GET', $brandRequest->getMethod());
        $this->assertEquals('Bearer test_token', $brandRequest->getHeader('Authorization')[0]);
        $this->assertEquals('application/ld+json', $brandRequest->getHeader('Content-Type')[0]);
        $this->assertEquals(
            sprintf('%s/stores/%s/brands', $baseUrl, $storeId),
            (string)$brandRequest->getUri()
        );

        $this->assertEquals(1, $response->getTotalItems());
        $this->assertCount(1, $response->getMembers());
        $this->assertEquals('Test Brand', $response->getMembers()[0]->getName());
        $this->assertEquals($brandId, $response->getMembers()[0]->getUuid()->toString());
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     */
    public function testFindOneByBrandFlow(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'access_token' => 'test_token',
            'expires_in' => 3600,
        ])));

        $storeId = '9d024f41-3faf-4eef-9d2d-a7e506b81afb';
        $brandId = 'e548e809-2ab1-4832-8dd9-f67115da61fb';
        $baseUrl = 'https://api.test/api/v1';

        $brandData = [
            '@context' => '/api/contexts/Brand',
            '@id' => '/api/brands',
            '@type' => 'Collection',
            'member' => [[
                '@id' => sprintf('%s/stores/%s/brands/%s', $baseUrl, $storeId, $brandId),
                '@type' => 'Brand',
                'uuid' => $brandId,
                'createdAt' => '2024-12-17T09:18:32+00:00',
                'updatedAt' => '2024-12-17T09:18:32+00:00',
                'name' => 'Test Brand',
                'store' => sprintf('%s/stores/%s', $baseUrl, $storeId),
                'description' => 'Test Brand Description',
                'image' => sprintf('%s/files/%s', $baseUrl, 'abc123'),
            ]],
            'totalItems' => 1,
        ];
        $this->mockHandler->append(new Response(200, [], json_encode($brandData)));

        $request = new BrandRequest(
            name: 'Test Brand',
            store: sprintf('api/v1/stores/%s', $storeId),
        );

        $params = new QueryParameters(filters: ['name' => 'Test Brand']);
        $response = $this->sdk->brand()->findOneBy($request, $params);

        $this->assertCount(2, $this->requestHistory);

        $authRequest = $this->requestHistory[0]['request'];
        $this->assertEquals('POST', $authRequest->getMethod());
        $this->assertEquals('https://auth.test/oauth/token', (string)$authRequest->getUri());

        $brandRequest = $this->requestHistory[1]['request'];
        $this->assertEquals('GET', $brandRequest->getMethod());
        $this->assertEquals('Bearer test_token', $brandRequest->getHeader('Authorization')[0]);
        $this->assertEquals('application/ld+json', $brandRequest->getHeader('Content-Type')[0]);
        $this->assertEquals(
            sprintf(
                '%s/stores/%s/brands?filters%%5Bname%%5D=%s',
                $baseUrl,
                $storeId,
                rawurlencode('Test Brand')
            ),
            (string)$brandRequest->getUri()
        );

        $this->assertEquals('Test Brand', $response->getName());
        $this->assertEquals($brandId, $response->getUuid()->toString());
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     */
    public function testCreateBrandFlow(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'access_token' => 'test_token',
            'expires_in' => 3600,
        ])));

        $storeId = '9d024f41-3faf-4eef-9d2d-a7e506b81afb';
        $brandId = 'e548e809-2ab1-4832-8dd9-f67115da61fb';
        $baseUrl = 'https://api.test/api/v1';

        $brandData = [
            '@id' => sprintf('%s/stores/%s/brands/%s', $baseUrl, $storeId, $brandId),
            '@type' => 'Brand',
            'uuid' => $brandId,
            'createdAt' => '2024-12-17T09:18:32+00:00',
            'updatedAt' => '2024-12-17T09:18:32+00:00',
            'name' => 'Test Brand',
            'store' => sprintf('%s/stores/%s', $baseUrl, $storeId),
            'description' => 'Test Brand Description',
            'image' => sprintf('%s/files/%s', $baseUrl, 'abc123'),
        ];
        $this->mockHandler->append(new Response(201, [], json_encode($brandData)));

        $request = new BrandRequest(
            name: 'Test Brand',
            store: sprintf('api/v1/stores/%s', $storeId),
            description: 'Test Brand Description',
            image: sprintf('/api/v1/files/%s', 'abc123')
        );

        $response = $this->sdk->brand()->create($request);

        $this->assertCount(2, $this->requestHistory);

        $authRequest = $this->requestHistory[0]['request'];
        $this->assertEquals('POST', $authRequest->getMethod());
        $this->assertEquals('https://auth.test/oauth/token', (string)$authRequest->getUri());

        $brandRequest = $this->requestHistory[1]['request'];
        $this->assertEquals('POST', $brandRequest->getMethod());
        $this->assertEquals('Bearer test_token', $brandRequest->getHeader('Authorization')[0]);
        $this->assertEquals('application/ld+json', $brandRequest->getHeader('Content-Type')[0]);
        $this->assertEquals(
            sprintf('%s/stores/%s/brands', $baseUrl, $storeId),
            (string)$brandRequest->getUri()
        );

        $this->assertEquals('Test Brand', $response->getName());
        $this->assertEquals('Test Brand Description', $response->getDescription());
        $this->assertEquals($brandId, $response->getUuid()->toString());
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     */
    public function testUpdateBrandFlow(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'access_token' => 'test_token',
            'expires_in' => 3600,
        ])));

        $storeId = '9d024f41-3faf-4eef-9d2d-a7e506b81afb';
        $brandId = 'e548e809-2ab1-4832-8dd9-f67115da61fb';
        $baseUrl = 'https://api.test/api/v1';

        $brandData = [
            '@id' => sprintf('%s/stores/%s/brands/%s', $baseUrl, $storeId, $brandId),
            '@type' => 'Brand',
            'uuid' => $brandId,
            'createdAt' => '2024-12-17T09:18:32+00:00',
            'updatedAt' => '2024-12-17T09:18:32+00:00',
            'name' => 'Updated Brand',
            'store' => sprintf('%s/stores/%s', $baseUrl, $storeId),
            'description' => 'Updated Description',
            'image' => sprintf('%s/files/%s', $baseUrl, 'abc123'),
        ];
        $this->mockHandler->append(new Response(200, [], json_encode($brandData)));

        $request = new BrandRequest(
            name: 'Updated Brand',
            description: 'Updated Description',
            iri: sprintf('%s/stores/%s/brands/%s', $baseUrl, $storeId, $brandId)
        );

        $response = $this->sdk->brand()->update($request);

        $this->assertCount(2, $this->requestHistory);

        $authRequest = $this->requestHistory[0]['request'];
        $this->assertEquals('POST', $authRequest->getMethod());
        $this->assertEquals('https://auth.test/oauth/token', (string)$authRequest->getUri());

        $brandRequest = $this->requestHistory[1]['request'];
        $this->assertEquals('PATCH', $brandRequest->getMethod());
        $this->assertEquals('Bearer test_token', $brandRequest->getHeader('Authorization')[0]);
        $this->assertEquals('application/merge-patch+json', $brandRequest->getHeader('Content-Type')[0]);
        $this->assertEquals(
            sprintf(
                '%s/stores/%s/brands/%s',
                $baseUrl,
                $storeId,
                $brandId
            ),
            (string)$brandRequest->getUri()
        );

        $this->assertEquals('Updated Brand', $response->getName());
        $this->assertEquals('Updated Description', $response->getDescription());
        $this->assertEquals($brandId, $response->getUuid()->toString());
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     */
    public function testDeleteBrandFlow(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'access_token' => 'test_token',
            'expires_in' => 3600,
        ])));

        $this->mockHandler->append(new Response(204));

        $storeId = '9d024f41-3faf-4eef-9d2d-a7e506b81afb';
        $brandId = 'e548e809-2ab1-4832-8dd9-f67115da61fb';
        $baseUrl = 'https://api.test/api/v1';

        $request = new BrandRequest(
            name: 'Test Brand',
            iri: sprintf('%s/stores/%s/brands/%s', $baseUrl, $storeId, $brandId)
        );

        $this->sdk->brand()->delete($request);

        $this->assertCount(2, $this->requestHistory);

        $authRequest = $this->requestHistory[0]['request'];
        $this->assertEquals('POST', $authRequest->getMethod());
        $this->assertEquals('https://auth.test/oauth/token', (string)$authRequest->getUri());

        $brandRequest = $this->requestHistory[1]['request'];
        $this->assertEquals('DELETE', $brandRequest->getMethod());
        $this->assertEquals('Bearer test_token', $brandRequest->getHeader('Authorization')[0]);
        $this->assertEquals(
            sprintf('%s/stores/%s/brands/%s', $baseUrl, $storeId, $brandId),
            (string)$brandRequest->getUri()
        );
    }

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     * @throws \Apiera\Sdk\Exception\ClientException
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
