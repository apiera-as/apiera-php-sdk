<?php

declare(strict_types=1);

namespace Tests\Integration\Resource;

use Apiera\Sdk\ApieraSdk;
use Apiera\Sdk\Configuration;
use Apiera\Sdk\DTO\QueryParameters;
use Apiera\Sdk\DTO\Request\AttributeTerm\AttributeTermRequest;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;

final class AttributeTermResourceTest extends TestCase
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
    public function testFindAttributeTermsFlow(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'access_token' => 'test_token',
            'expires_in' => 3600,
        ])));

        $attributeId = '9d024f41-3faf-4eef-9d2d-a7e506b81afb';
        $termId = 'e548e809-2ab1-4832-8dd9-f67115da61fb';
        $storeId = '123e4567-e89b-12d3-a456-426614174000';
        $baseUrl = 'https://api.test/api/v1';

        $attributeTermData = [
            '@context' => '/api/contexts/AttributeTerm',
            '@id' => '/api/v1/attributes/123/terms',
            '@type' => 'Collection',
            'member' => [[
                '@id' => sprintf('%s/stores/%s/attributes/%s/terms/%s', $baseUrl, $storeId, $attributeId, $termId),
                '@type' => 'AttributeTerm',
                'uuid' => $termId,
                'createdAt' => '2024-12-17T09:18:32+00:00',
                'updatedAt' => '2024-12-17T09:18:32+00:00',
                'name' => 'Example term',
                'attribute' => sprintf('%s/stores/%s/attributes/%s', $baseUrl, $storeId, $attributeId),
                'store' => sprintf('%s/stores/%s', $baseUrl, $storeId),
            ]],
            'totalItems' => 1,
        ];
        $this->mockHandler->append(new Response(200, [], json_encode($attributeTermData)));

        $request = new AttributeTermRequest(
            name: 'Example term',
            attribute: sprintf('/api/v1/stores/%s/attributes/%s', $storeId, $attributeId)
        );

        $response = $this->sdk->attributeTerm()->find($request);

        $this->assertCount(2, $this->requestHistory);

        $authRequest = $this->requestHistory[0]['request'];
        $this->assertEquals('POST', $authRequest->getMethod());
        $this->assertEquals('https://auth.test/oauth/token', (string)$authRequest->getUri());

        $attributeTermRequest = $this->requestHistory[1]['request'];
        $this->assertEquals('GET', $attributeTermRequest->getMethod());
        $this->assertEquals('Bearer test_token', $attributeTermRequest->getHeader('Authorization')[0]);
        $this->assertEquals('application/ld+json', $attributeTermRequest->getHeader('Content-Type')[0]);
        $this->assertEquals(
            sprintf('%s/stores/%s/attributes/%s/terms', $baseUrl, $storeId, $attributeId),
            (string)$attributeTermRequest->getUri()
        );

        $this->assertEquals(1, $response->getLdTotalItems());
        $this->assertCount(1, $response->getLdMembers());
        $this->assertEquals('Example term', $response->getLdMembers()[0]->getName());
        $this->assertEquals($termId, $response->getLdMembers()[0]->getUuid()->toString());
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function testFindOneByAttributeTermFlow(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'access_token' => 'test_token',
            'expires_in' => 3600,
        ])));

        $attributeId = '9d024f41-3faf-4eef-9d2d-a7e506b81afb';
        $termId = 'e548e809-2ab1-4832-8dd9-f67115da61fb';
        $storeId = '123e4567-e89b-12d3-a456-426614174000';
        $baseUrl = 'https://api.test/api/v1';

        $attributeTermData = [
            '@context' => '/api/contexts/AttributeTerm',
            '@id' => '/api/v1/attributes/123/terms',
            '@type' => 'Collection',
            'member' => [[
                '@id' => sprintf('%s/stores/%s/attributes/%s/terms/%s', $baseUrl, $storeId, $attributeId, $termId),
                '@type' => 'AttributeTerm',
                'uuid' => $termId,
                'createdAt' => '2024-12-17T09:18:32+00:00',
                'updatedAt' => '2024-12-17T09:18:32+00:00',
                'name' => 'Example term',
                'attribute' => sprintf('%s/stores/%s/attributes/%s', $baseUrl, $storeId, $attributeId),
                'store' => sprintf('%s/stores/%s', $baseUrl, $storeId),
            ]],
            'totalItems' => 1,
        ];
        $this->mockHandler->append(new Response(200, [], json_encode($attributeTermData)));

        $request = new AttributeTermRequest(
            name: 'Example term',
            attribute: sprintf('/api/v1/stores/%s/attributes/%s', $storeId, $attributeId)
        );

        $params = new QueryParameters(filters: ['name' => 'Example term']);
        $response = $this->sdk->attributeTerm()->findOneBy($request, $params);

        $this->assertCount(2, $this->requestHistory);

        $authRequest = $this->requestHistory[0]['request'];
        $this->assertEquals('POST', $authRequest->getMethod());
        $this->assertEquals('https://auth.test/oauth/token', (string)$authRequest->getUri());

        $attributeTermRequest = $this->requestHistory[1]['request'];
        $this->assertEquals('GET', $attributeTermRequest->getMethod());
        $this->assertEquals('Bearer test_token', $attributeTermRequest->getHeader('Authorization')[0]);
        $this->assertEquals('application/ld+json', $attributeTermRequest->getHeader('Content-Type')[0]);
        $this->assertEquals(
            sprintf(
                '%s/stores/%s/attributes/%s/terms?filters%%5Bname%%5D=%s',
                $baseUrl,
                $storeId,
                $attributeId,
                rawurlencode('Example term')
            ),
            (string)$attributeTermRequest->getUri()
        );

        $this->assertEquals('Example term', $response->getName());
        $this->assertEquals($termId, $response->getUuid()->toString());
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function testCreateAttributeTermFlow(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'access_token' => 'test_token',
            'expires_in' => 3600,
        ])));

        $attributeId = '9d024f41-3faf-4eef-9d2d-a7e506b81afb';
        $termId = 'e548e809-2ab1-4832-8dd9-f67115da61fb';
        $storeId = '123e4567-e89b-12d3-a456-426614174000';
        $baseUrl = 'https://api.test/api/v1';

        $attributeTermData = [
            '@id' => sprintf('%s/stores/%s/attributes/%s/terms/%s', $baseUrl, $storeId, $attributeId, $termId),
            '@type' => 'AttributeTerm',
            'uuid' => $termId,
            'createdAt' => '2024-12-17T09:18:32+00:00',
            'updatedAt' => '2024-12-17T09:18:32+00:00',
            'name' => 'Example term',
            'attribute' => sprintf('%s/stores/%s/attributes/%s', $baseUrl, $storeId, $attributeId),
            'store' => sprintf('%s/stores/%s', $baseUrl, $storeId),
        ];
        $this->mockHandler->append(new Response(201, [], json_encode($attributeTermData)));

        $request = new AttributeTermRequest(
            name: 'Example term',
            attribute: sprintf('/api/v1/stores/%s/attributes/%s', $storeId, $attributeId)
        );

        $response = $this->sdk->attributeTerm()->create($request);

        $this->assertCount(2, $this->requestHistory);

        $authRequest = $this->requestHistory[0]['request'];
        $this->assertEquals('POST', $authRequest->getMethod());
        $this->assertEquals('https://auth.test/oauth/token', (string)$authRequest->getUri());

        $attributeTermRequest = $this->requestHistory[1]['request'];
        $this->assertEquals('POST', $attributeTermRequest->getMethod());
        $this->assertEquals('Bearer test_token', $attributeTermRequest->getHeader('Authorization')[0]);
        $this->assertEquals('application/ld+json', $attributeTermRequest->getHeader('Content-Type')[0]);
        $this->assertEquals(
            sprintf('%s/stores/%s/attributes/%s/terms', $baseUrl, $storeId, $attributeId),
            (string)$attributeTermRequest->getUri()
        );

        $this->assertEquals('Example term', $response->getName());
        $this->assertEquals($termId, $response->getUuid()->toString());
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function testUpdateAttributeTermFlow(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'access_token' => 'test_token',
            'expires_in' => 3600,
        ])));

        $attributeId = '9d024f41-3faf-4eef-9d2d-a7e506b81afb';
        $termId = 'e548e809-2ab1-4832-8dd9-f67115da61fb';
        $storeId = '123e4567-e89b-12d3-a456-426614174000';
        $baseUrl = 'https://api.test/api/v1';

        $attributeTermData = [
            '@id' => sprintf('%s/stores/%s/attributes/%s/terms/%s', $baseUrl, $storeId, $attributeId, $termId),
            '@type' => 'AttributeTerm',
            'uuid' => $termId,
            'createdAt' => '2024-12-17T09:18:32+00:00',
            'updatedAt' => '2024-12-17T09:18:32+00:00',
            'name' => 'Updated term',
            'attribute' => sprintf('%s/stores/%s/attributes/%s', $baseUrl, $storeId, $attributeId),
            'store' => sprintf('%s/stores/%s', $baseUrl, $storeId),
        ];
        $this->mockHandler->append(new Response(200, [], json_encode($attributeTermData)));

        $request = new AttributeTermRequest(
            name: 'Updated term',
            iri: sprintf('%s/stores/%s/attributes/%s/terms/%s', $baseUrl, $storeId, $attributeId, $termId)
        );

        $response = $this->sdk->attributeTerm()->update($request);

        $this->assertCount(2, $this->requestHistory);

        $authRequest = $this->requestHistory[0]['request'];
        $this->assertEquals('POST', $authRequest->getMethod());
        $this->assertEquals('https://auth.test/oauth/token', (string)$authRequest->getUri());

        $attributeTermRequest = $this->requestHistory[1]['request'];
        $this->assertEquals('PATCH', $attributeTermRequest->getMethod());
        $this->assertEquals('Bearer test_token', $attributeTermRequest->getHeader('Authorization')[0]);
        $this->assertEquals('application/merge-patch+json', $attributeTermRequest->getHeader('Content-Type')[0]);
        $this->assertEquals(
            sprintf(
                '%s/stores/%s/attributes/%s/terms/%s',
                $baseUrl,
                $storeId,
                $attributeId,
                $termId
            ),
            (string)$attributeTermRequest->getUri()
        );

        $this->assertEquals('Updated term', $response->getName());
        $this->assertEquals($termId, $response->getUuid()->toString());
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function testDeleteAttributeTermFlow(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'access_token' => 'test_token',
            'expires_in' => 3600,
        ])));

        $this->mockHandler->append(new Response(204));

        $attributeId = '9d024f41-3faf-4eef-9d2d-a7e506b81afb';
        $termId = 'e548e809-2ab1-4832-8dd9-f67115da61fb';
        $storeId = '123e4567-e89b-12d3-a456-426614174000';
        $baseUrl = 'https://api.test/api/v1';

        $request = new AttributeTermRequest(
            name: 'Example term',
            iri: sprintf('%s/stores/%s/attributes/%s/terms/%s', $baseUrl, $storeId, $attributeId, $termId)
        );

        $this->sdk->attributeTerm()->delete($request);

        $this->assertCount(2, $this->requestHistory);

        $authRequest = $this->requestHistory[0]['request'];
        $this->assertEquals('POST', $authRequest->getMethod());
        $this->assertEquals('https://auth.test/oauth/token', (string)$authRequest->getUri());

        $attributeTermRequest = $this->requestHistory[1]['request'];
        $this->assertEquals('DELETE', $attributeTermRequest->getMethod());
        $this->assertEquals('Bearer test_token', $attributeTermRequest->getHeader('Authorization')[0]);
        $this->assertEquals(
            sprintf('%s/stores/%s/attributes/%s/terms/%s', $baseUrl, $storeId, $attributeId, $termId),
            (string)$attributeTermRequest->getUri()
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
