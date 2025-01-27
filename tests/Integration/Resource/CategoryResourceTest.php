<?php

declare(strict_types=1);

namespace Tests\Integration\Resource;

use Apiera\Sdk\ApieraSdk;
use Apiera\Sdk\Configuration;
use Apiera\Sdk\DTO\QueryParameters;
use Apiera\Sdk\DTO\Request\Category\CategoryRequest;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;

final class CategoryResourceTest extends TestCase
{
    private MockHandler $mockHandler;

    /** @var array<int, array<string, mixed>>|\ArrayAccess<int, array<string, mixed>> */
    private array|\ArrayAccess $requestHistory = [];
    private ApieraSdk $sdk;

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

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     */
    public function testFindCategoriesFlow(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'access_token' => 'test_token',
            'expires_in' => 3600,
        ])));

        $storeId = '9d024f41-3faf-4eef-9d2d-a7e506b81afb';
        $categoryId = 'e548e809-2ab1-4832-8dd9-f67115da61fb';
        $baseUrl = 'https://api.test/api/v1';

        $categoryData = [
            '@context' => '/api/contexts/Category',
            '@id' => '/api/categories',
            '@type' => 'Collection',
            'member' => [[
                '@id' => sprintf('%s/stores/%s/categories/%s', $baseUrl, $storeId, $categoryId),
                '@type' => 'Category',
                'uuid' => $categoryId,
                'createdAt' => '2024-12-17T09:18:32+00:00',
                'updatedAt' => '2024-12-17T09:18:32+00:00',
                'name' => 'Test category',
                'description' => 'Test category description',
                'parent' => null,
                'store' => sprintf('%s/stores/%s', $baseUrl, $storeId),
                'image' => null,
            ]],
            'totalItems' => 1,
        ];
        $this->mockHandler->append(new Response(200, [], json_encode($categoryData)));

        $request = new CategoryRequest(
            name: 'Test category',
            store: sprintf('/api/v1/stores/%s', $storeId)
        );

        $response = $this->sdk->category()->find($request);

        // Verify number of requests
        $this->assertCount(2, $this->requestHistory);

        // Verify auth request
        $authRequest = $this->requestHistory[0]['request'];
        $this->assertEquals('POST', $authRequest->getMethod());
        $this->assertEquals('https://auth.test/oauth/token', (string)$authRequest->getUri());

        // Verify category find request
        $categoryRequest = $this->requestHistory[1]['request'];
        $this->assertEquals('GET', $categoryRequest->getMethod());
        $this->assertEquals('Bearer test_token', $categoryRequest->getHeader('Authorization')[0]);
        $this->assertEquals('application/ld+json', $categoryRequest->getHeader('Content-Type')[0]);
        $this->assertEquals(
            sprintf('%s/stores/%s/attributes', $baseUrl, $storeId),
            (string)$categoryRequest->getUri()
        );

        // Verify response
        $this->assertEquals(1, $response->getTotalItems());
        $this->assertCount(1, $response->getMembers());
        $this->assertEquals('Test category', $response->getMembers()[0]->getName());
        $this->assertEquals($categoryId, $response->getMembers()[0]->getUuid()->toString());
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     */
    public function testFindOneByCategoryFlow(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'access_token' => 'test_token',
            'expires_in' => 3600,
        ])));

        $storeId = '9d024f41-3faf-4eef-9d2d-a7e506b81afb';
        $categoryId = 'e548e809-2ab1-4832-8dd9-f67115da61fb';
        $baseUrl = 'https://api.test/api/v1';

        $categoryData = [
            '@context' => '/api/contexts/Category',
            '@id' => '/api/categories',
            '@type' => 'Collection',
            'member' => [[
                '@id' => sprintf('%s/stores/%s/categories/%s', $baseUrl, $storeId, $categoryId),
                '@type' => 'Category',
                'uuid' => $categoryId,
                'createdAt' => '2024-12-17T09:18:32+00:00',
                'updatedAt' => '2024-12-17T09:18:32+00:00',
                'name' => 'Test category',
                'description' => 'Test category description',
                'parent' => null,
                'store' => sprintf('%s/stores/%s', $baseUrl, $storeId),
                'image' => null,
            ]],
            'totalItems' => 1,
        ];
        $this->mockHandler->append(new Response(200, [], json_encode($categoryData)));

        $request = new CategoryRequest(
            name: 'Test category',
            store: sprintf('api/v1/stores/%s', $storeId),
        );

        $params = new QueryParameters(filters: ['name' => 'Test category']);
        $response = $this->sdk->category()->findOneBy($request, $params);

        $this->assertCount(2, $this->requestHistory);

        $authRequest = $this->requestHistory[0]['request'];
        $this->assertEquals('POST', $authRequest->getMethod());
        $this->assertEquals('https://auth.test/oauth/token', (string)$authRequest->getUri());

        $categoryRequest = $this->requestHistory[1]['request'];
        $this->assertEquals('GET', $categoryRequest->getMethod());
        $this->assertEquals('Bearer test_token', $categoryRequest->getHeader('Authorization')[0]);
        $this->assertEquals('application/ld+json', $categoryRequest->getHeader('Content-Type')[0]);
        $this->assertEquals(
            sprintf(
                '%s/stores/%s/categories?filters%%5Bname%%5D=%s',
                $baseUrl,
                $storeId,
                rawurlencode('Test category')
            ),
            (string)$categoryRequest->getUri()
        );

        $this->assertEquals('Test category', $response->getName());
        $this->assertEquals($categoryId, $response->getUuid()->toString());
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     */
    public function testCreateCategoryFlow(): void
    {
        // Setup responses for auth and category creation
        $this->mockHandler->append(new Response(200, [], json_encode([
            'access_token' => 'test_token',
            'expires_in' => 3600,
        ])));

        $storeId = '9d024f41-3faf-4eef-9d2d-a7e506b81afb';
        $categoryId = 'e548e809-2ab1-4832-8dd9-f67115da61fb';
        $baseUrl = 'https://api.test/api/v1';

        $categoryData = [
            '@id' => sprintf('%s/stores/%s/categories/%s', $baseUrl, $storeId, $categoryId),
            '@type' => 'Category',
            'uuid' => $categoryId,
            'createdAt' => '2024-12-17T09:18:32+00:00',
            'updatedAt' => '2024-12-17T09:18:32+00:00',
            'name' => 'Test category',
            'description' => 'Test category description',
            'parent' => null,
            'store' => sprintf('%s/stores/%s', $baseUrl, $storeId),
            'image' => null,
        ];
        $this->mockHandler->append(new Response(201, [], json_encode($categoryData)));

        $request = new CategoryRequest(
            name: 'Test category',
            store: sprintf('api/v1/stores/%s', $storeId),
            description: 'Test category description',
        );

        $response = $this->sdk->category()->create($request);

        // Verify number of requests
        $this->assertCount(2, $this->requestHistory);

        // Verify auth request
        $authRequest = $this->requestHistory[0]['request'];
        $this->assertEquals('POST', $authRequest->getMethod());
        $this->assertEquals('https://auth.test/oauth/token', (string)$authRequest->getUri());

        // Verify category creation request
        $categoryRequest = $this->requestHistory[1]['request'];
        $this->assertEquals('POST', $categoryRequest->getMethod());
        $this->assertEquals('Bearer test_token', $categoryRequest->getHeader('Authorization')[0]);
        $this->assertEquals('application/ld+json', $categoryRequest->getHeader('Content-Type')[0]);
        $this->assertEquals(
            sprintf('%s/stores/%s/categories', $baseUrl, $storeId),
            (string)$categoryRequest->getUri()
        );

        // Verify response
        $this->assertEquals('Test category', $response->getName());
        $this->assertEquals('Test category description', $response->getDescription());
        $this->assertEquals($categoryId, $response->getUuid()->toString());
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     */
    public function testUpdateCategoryFlow(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'access_token' => 'test_token',
            'expires_in' => 3600,
        ])));

        $storeId = '9d024f41-3faf-4eef-9d2d-a7e506b81afb';
        $categoryId = 'e548e809-2ab1-4832-8dd9-f67115da61fb';
        $baseUrl = 'https://api.test/api/v1';

        $categoryData = [
            '@id' => sprintf('%s/stores/%s/categories/%s', $baseUrl, $storeId, $categoryId),
            '@type' => 'Category',
            'uuid' => $categoryId,
            'createdAt' => '2024-12-17T09:18:32+00:00',
            'updatedAt' => '2024-12-17T09:18:32+00:00',
            'name' => 'Updated category',
            'description' => 'Updated description',
            'parent' => null,
            'store' => sprintf('%s/stores/%s', $baseUrl, $storeId),
            'image' => null,
        ];
        $this->mockHandler->append(new Response(200, [], json_encode($categoryData)));

        $request = new CategoryRequest(
            name: 'Updated category',
            description: 'Updated description',
            iri: sprintf('%s/stores/%s/categories/%s', $baseUrl, $storeId, $categoryId)
        );

        $response = $this->sdk->category()->update($request);

        // Verify number of requests
        $this->assertCount(2, $this->requestHistory);

        // Verify auth request
        $authRequest = $this->requestHistory[0]['request'];
        $this->assertEquals('POST', $authRequest->getMethod());
        $this->assertEquals('https://auth.test/oauth/token', (string)$authRequest->getUri());

        // Verify category update request
        $categoryRequest = $this->requestHistory[1]['request'];
        $this->assertEquals('PATCH', $categoryRequest->getMethod());
        $this->assertEquals('Bearer test_token', $categoryRequest->getHeader('Authorization')[0]);
        $this->assertEquals('application/merge-patch+json', $categoryRequest->getHeader('Content-Type')[0]);
        $this->assertEquals(
            sprintf(
                '%s/stores/%s/categories/%s',
                $baseUrl,
                $storeId,
                $categoryId
            ),
            (string)$categoryRequest->getUri()
        );

        // Verify response
        $this->assertEquals('Updated category', $response->getName());
        $this->assertEquals('Updated description', $response->getDescription());
        $this->assertEquals('e548e809-2ab1-4832-8dd9-f67115da61fb', $response->getUuid()->toString());
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     */
    public function testDeleteCategoryFlow(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'access_token' => 'test_token',
            'expires_in' => 3600,
        ])));

        $this->mockHandler->append(new Response(204));

        $baseUrl = 'https://api.test/api/v1';
        $storeId = '9d024f41-3faf-4eef-9d2d-a7e506b81afb';
        $categoryId = 'e548e809-2ab1-4832-8dd9-f67115da61fb';

        $request = new CategoryRequest(
            name: 'Test category',
            iri: sprintf('%s/stores/%s/categories/%s', $baseUrl, $storeId, $categoryId)
        );

        $this->sdk->category()->delete($request);

        // Verify number of requests
        $this->assertCount(2, $this->requestHistory);

        // Verify auth request
        $authRequest = $this->requestHistory[0]['request'];
        $this->assertEquals('POST', $authRequest->getMethod());
        $this->assertEquals('https://auth.test/oauth/token', (string)$authRequest->getUri());

        // Verify category delete request
        $categoryRequest = $this->requestHistory[1]['request'];
        $this->assertEquals('DELETE', $categoryRequest->getMethod());
        $this->assertEquals('Bearer test_token', $categoryRequest->getHeader('Authorization')[0]);
        $this->assertEquals(
            sprintf('%s/stores/%s/categories/%s', $baseUrl, $storeId, $categoryId),
            (string)$categoryRequest->getUri()
        );
    }
}
