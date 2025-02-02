<?php

declare(strict_types=1);

namespace Tests\Integration\Resource;

use Apiera\Sdk\ApieraSdk;
use Apiera\Sdk\Configuration;
use Apiera\Sdk\DTO\QueryParameters;
use Apiera\Sdk\DTO\Request\AlternateIdentifier\AlternateIdentifierRequest;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;

final class AlternateIdentifierResourceTest extends TestCase
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
    public function testFindAlternateIdentifiersFlow(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'access_token' => 'test_token',
            'expires_in' => 3600,
        ])));

        $alternateIdentifierData = [
            '@context' => '/api/contexts/AlternateIdentifier',
            '@id' => '/api/v1/alternate_identifiers',
            '@type' => 'Collection',
            'member' => [[
                '@id' => 'https://api.test/api/v1/alternate_identifiers/e548e809-2ab1-4832-8dd9-f67115da61fb',
                '@type' => 'AlternateIdentifier',
                'uuid' => 'e548e809-2ab1-4832-8dd9-f67115da61fb',
                'createdAt' => '2024-12-17T09:18:32+00:00',
                'updatedAt' => '2024-12-17T09:18:32+00:00',
                'type' => 'gtin',
                'code' => 'ABC123',
            ]],
            'totalItems' => 1,
        ];
        $this->mockHandler->append(new Response(200, [], json_encode($alternateIdentifierData)));

        $params = new QueryParameters(filters: ['code' => 'ABC123']);
        $response = $this->sdk->alternateIdentifier()->find(new AlternateIdentifierRequest(
            code: 'ABC123',
            type: 'gtin',
        ), $params);

        $this->assertCount(2, $this->requestHistory);

        $authRequest = $this->requestHistory[0]['request'];
        $this->assertEquals('POST', $authRequest->getMethod());
        $this->assertEquals('https://auth.test/oauth/token', (string)$authRequest->getUri());

        $alternateIdentifierRequest = $this->requestHistory[1]['request'];
        $this->assertEquals('GET', $alternateIdentifierRequest->getMethod());
        $this->assertEquals('Bearer test_token', $alternateIdentifierRequest->getHeader('Authorization')[0]);
        $this->assertEquals('application/ld+json', $alternateIdentifierRequest->getHeader('Content-Type')[0]);
        $this->assertEquals(
            'https://api.test/api/v1/alternate_identifiers?filters%5Bcode%5D=ABC123',
            (string)$alternateIdentifierRequest->getUri()
        );

        $this->assertEquals(1, $response->getTotalItems());
        $this->assertCount(1, $response->getMembers());
        $this->assertEquals('ABC123', $response->getMembers()[0]->getCode());
        $this->assertEquals('gtin', $response->getMembers()[0]->getType());
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function testFindOneByAlternateIdentifierFlow(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'access_token' => 'test_token',
            'expires_in' => 3600,
        ])));

        $alternateIdentifierData = [
            '@context' => '/api/contexts/AlternateIdentifier',
            '@id' => '/api/alternate_identifiers',
            '@type' => 'Collection',
            'member' => [[
                '@id' => 'https://api.test/api/v1/alternate_identifiers/e548e809-2ab1-4832-8dd9-f67115da61fb',
                '@type' => 'AlternateIdentifier',
                'uuid' => 'e548e809-2ab1-4832-8dd9-f67115da61fb',
                'createdAt' => '2024-12-17T09:18:32+00:00',
                'updatedAt' => '2024-12-17T09:18:32+00:00',
                'type' => 'gtin',
                'code' => 'ABC123',
            ]],
            'totalItems' => 1,
        ];
        $this->mockHandler->append(new Response(200, [], json_encode($alternateIdentifierData)));

        $params = new QueryParameters(filters: ['code' => 'ABC123']);
        $response = $this->sdk->alternateIdentifier()->findOneBy(
            new AlternateIdentifierRequest(code: 'ABC123', type: 'gtin'),
            $params
        );

        $this->assertCount(2, $this->requestHistory);

        $authRequest = $this->requestHistory[0]['request'];
        $this->assertEquals('POST', $authRequest->getMethod());
        $this->assertEquals('https://auth.test/oauth/token', (string)$authRequest->getUri());

        $alternateIdentifierRequest = $this->requestHistory[1]['request'];
        $this->assertEquals('GET', $alternateIdentifierRequest->getMethod());
        $this->assertEquals('Bearer test_token', $alternateIdentifierRequest->getHeader('Authorization')[0]);
        $this->assertEquals('application/ld+json', $alternateIdentifierRequest->getHeader('Content-Type')[0]);
        $this->assertEquals(
            'https://api.test/api/v1/alternate_identifiers?filters%5Bcode%5D=ABC123',
            (string)$alternateIdentifierRequest->getUri()
        );

        $this->assertEquals('ABC123', $response->getCode());
        $this->assertEquals('gtin', $response->getType());
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function testCreateAlternateIdentifierFlow(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'access_token' => 'test_token',
            'expires_in' => 3600,
        ])));

        $alternateIdentifierData = [
            '@id' => 'https://api.test/api/v1/alternate_identifiers/e548e809-2ab1-4832-8dd9-f67115da61fb',
            '@type' => 'AlternateIdentifier',
            'uuid' => 'e548e809-2ab1-4832-8dd9-f67115da61fb',
            'createdAt' => '2024-12-17T09:18:32+00:00',
            'updatedAt' => '2024-12-17T09:18:32+00:00',
            'type' => 'gtin',
            'code' => 'ABC123',
        ];
        $this->mockHandler->append(new Response(201, [], json_encode($alternateIdentifierData)));

        $request = new AlternateIdentifierRequest(
            code: 'ABC123',
            type: 'gtin'
        );

        $response = $this->sdk->alternateIdentifier()->create($request);

        $this->assertCount(2, $this->requestHistory);

        $authRequest = $this->requestHistory[0]['request'];
        $this->assertEquals('POST', $authRequest->getMethod());
        $this->assertEquals('https://auth.test/oauth/token', (string)$authRequest->getUri());

        $alternateIdentifierRequest = $this->requestHistory[1]['request'];
        $this->assertEquals('POST', $alternateIdentifierRequest->getMethod());
        $this->assertEquals('Bearer test_token', $alternateIdentifierRequest->getHeader('Authorization')[0]);
        $this->assertEquals('application/ld+json', $alternateIdentifierRequest->getHeader('Content-Type')[0]);
        $this->assertEquals(
            'https://api.test/api/v1/alternate_identifiers',
            (string)$alternateIdentifierRequest->getUri()
        );

        $this->assertEquals('ABC123', $response->getCode());
        $this->assertEquals('gtin', $response->getType());
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function testUpdateAlternateIdentifierFlow(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'access_token' => 'test_token',
            'expires_in' => 3600,
        ])));

        $alternateIdentifierData = [
            '@id' => 'https://api.test/api/v1/alternate_identifiers/e548e809-2ab1-4832-8dd9-f67115da61fb',
            '@type' => 'AlternateIdentifier',
            'uuid' => 'e548e809-2ab1-4832-8dd9-f67115da61fb',
            'createdAt' => '2024-12-17T09:18:32+00:00',
            'updatedAt' => '2024-12-17T09:18:32+00:00',
            'type' => 'ean',
            'code' => 'XYZ789',
        ];
        $this->mockHandler->append(new Response(200, [], json_encode($alternateIdentifierData)));

        $request = new AlternateIdentifierRequest(
            code: 'XYZ789',
            type: 'ean',
            iri: 'https://api.test/api/v1/alternate_identifiers/e548e809-2ab1-4832-8dd9-f67115da61fb'
        );

        $response = $this->sdk->alternateIdentifier()->update($request);

        $this->assertCount(2, $this->requestHistory);

        $authRequest = $this->requestHistory[0]['request'];
        $this->assertEquals('POST', $authRequest->getMethod());
        $this->assertEquals('https://auth.test/oauth/token', (string)$authRequest->getUri());

        $alternateIdentifierRequest = $this->requestHistory[1]['request'];
        $this->assertEquals('PATCH', $alternateIdentifierRequest->getMethod());
        $this->assertEquals('Bearer test_token', $alternateIdentifierRequest->getHeader('Authorization')[0]);
        $this->assertEquals('application/merge-patch+json', $alternateIdentifierRequest->getHeader('Content-Type')[0]);
        $this->assertEquals(
            'https://api.test/api/v1/alternate_identifiers/e548e809-2ab1-4832-8dd9-f67115da61fb',
            (string)$alternateIdentifierRequest->getUri()
        );

        $this->assertEquals('XYZ789', $response->getCode());
        $this->assertEquals('ean', $response->getType());
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function testDeleteAlternateIdentifierFlow(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'access_token' => 'test_token',
            'expires_in' => 3600,
        ])));

        $this->mockHandler->append(new Response(204));

        $request = new AlternateIdentifierRequest(
            code: 'ABC123',
            type: 'gtin',
            iri: 'https://api.test/api/v1/alternate_identifiers/e548e809-2ab1-4832-8dd9-f67115da61fb'
        );

        $this->sdk->alternateIdentifier()->delete($request);

        $this->assertCount(2, $this->requestHistory);

        $authRequest = $this->requestHistory[0]['request'];
        $this->assertEquals('POST', $authRequest->getMethod());
        $this->assertEquals('https://auth.test/oauth/token', (string)$authRequest->getUri());

        $alternateIdentifierRequest = $this->requestHistory[1]['request'];
        $this->assertEquals('DELETE', $alternateIdentifierRequest->getMethod());
        $this->assertEquals('Bearer test_token', $alternateIdentifierRequest->getHeader('Authorization')[0]);
        $this->assertEquals(
            'https://api.test/api/v1/alternate_identifiers/e548e809-2ab1-4832-8dd9-f67115da61fb',
            (string)$alternateIdentifierRequest->getUri()
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
