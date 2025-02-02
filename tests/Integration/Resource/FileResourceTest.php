<?php

declare(strict_types=1);

namespace Tests\Integration\Resource;

use Apiera\Sdk\ApieraSdk;
use Apiera\Sdk\Configuration;
use Apiera\Sdk\DTO\QueryParameters;
use Apiera\Sdk\DTO\Request\File\FileRequest;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;

final class FileResourceTest extends TestCase
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
    public function testFindFilesFlow(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'access_token' => 'test_token',
            'expires_in' => 3600,
        ])));

        $fileId = 'e548e809-2ab1-4832-8dd9-f67115da61fb';
        $baseUrl = 'https://api.test/api/v1';

        $fileData = [
            '@context' => '/api/v1/contexts/File',
            '@id' => '/api/v1/files',
            '@type' => 'Collection',
            'member' => [[
                '@id' => sprintf('%s/files/%s', $baseUrl, $fileId),
                '@type' => 'File',
                'uuid' => $fileId,
                'createdAt' => '2024-12-17T09:18:32+00:00',
                'updatedAt' => '2024-12-17T09:18:32+00:00',
                'url' => 'https://example.com/test.jpg',
                'name' => 'test.jpg',
            ]],
            'totalItems' => 1,
        ];
        $this->mockHandler->append(new Response(200, [], json_encode($fileData)));

        $request = new FileRequest(
            url: 'https://example.com/test.jpg',
            name: 'test.jpg'
        );

        $response = $this->sdk->file()->find($request);

        $this->assertCount(2, $this->requestHistory);

        $authRequest = $this->requestHistory[0]['request'];
        $this->assertEquals('POST', $authRequest->getMethod());
        $this->assertEquals('https://auth.test/oauth/token', (string)$authRequest->getUri());

        $fileRequest = $this->requestHistory[1]['request'];
        $this->assertEquals('GET', $fileRequest->getMethod());
        $this->assertEquals('Bearer test_token', $fileRequest->getHeader('Authorization')[0]);
        $this->assertEquals('application/ld+json', $fileRequest->getHeader('Content-Type')[0]);
        $this->assertEquals(
            sprintf('%s/files', $baseUrl),
            (string)$fileRequest->getUri()
        );

        $this->assertEquals(1, $response->getTotalItems());
        $this->assertCount(1, $response->getMembers());
        $this->assertEquals('test.jpg', $response->getMembers()[0]->getName());
        $this->assertEquals('https://example.com/test.jpg', $response->getMembers()[0]->getUrl());
        $this->assertEquals($fileId, $response->getMembers()[0]->getUuid()->toString());
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function testFindOneByFileFlow(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'access_token' => 'test_token',
            'expires_in' => 3600,
        ])));

        $fileId = 'e548e809-2ab1-4832-8dd9-f67115da61fb';
        $baseUrl = 'https://api.test/api/v1';

        $fileData = [
            '@context' => '/api/v1/contexts/File',
            '@id' => '/api/v1/files',
            '@type' => 'Collection',
            'member' => [[
                '@id' => sprintf('%s/files/%s', $baseUrl, $fileId),
                '@type' => 'File',
                'uuid' => $fileId,
                'createdAt' => '2024-12-17T09:18:32+00:00',
                'updatedAt' => '2024-12-17T09:18:32+00:00',
                'url' => 'https://example.com/test.jpg',
                'name' => 'test.jpg',
            ]],
            'totalItems' => 1,
        ];
        $this->mockHandler->append(new Response(200, [], json_encode($fileData)));

        $request = new FileRequest(
            url: 'https://example.com/test.jpg',
            name: 'test.jpg'
        );

        $params = new QueryParameters(filters: ['name' => 'test.jpg']);
        $response = $this->sdk->file()->findOneBy($request, $params);

        $this->assertCount(2, $this->requestHistory);

        $authRequest = $this->requestHistory[0]['request'];
        $this->assertEquals('POST', $authRequest->getMethod());
        $this->assertEquals('https://auth.test/oauth/token', (string)$authRequest->getUri());

        $fileRequest = $this->requestHistory[1]['request'];
        $this->assertEquals('GET', $fileRequest->getMethod());
        $this->assertEquals('Bearer test_token', $fileRequest->getHeader('Authorization')[0]);
        $this->assertEquals('application/ld+json', $fileRequest->getHeader('Content-Type')[0]);
        $this->assertEquals(
            sprintf(
                '%s/files?filters%%5Bname%%5D=%s',
                $baseUrl,
                rawurlencode('test.jpg')
            ),
            (string)$fileRequest->getUri()
        );

        $this->assertEquals('test.jpg', $response->getName());
        $this->assertEquals('https://example.com/test.jpg', $response->getUrl());
        $this->assertEquals($fileId, $response->getUuid()->toString());
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function testCreateFileFlow(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'access_token' => 'test_token',
            'expires_in' => 3600,
        ])));

        $fileId = 'e548e809-2ab1-4832-8dd9-f67115da61fb';
        $baseUrl = 'https://api.test/api/v1';

        $fileData = [
            '@id' => sprintf('%s/files/%s', $baseUrl, $fileId),
            '@type' => 'File',
            'uuid' => $fileId,
            'createdAt' => '2024-12-17T09:18:32+00:00',
            'updatedAt' => '2024-12-17T09:18:32+00:00',
            'url' => 'https://example.com/test.jpg',
            'name' => 'test.jpg',
        ];
        $this->mockHandler->append(new Response(201, [], json_encode($fileData)));

        $request = new FileRequest(
            url: 'https://example.com/test.jpg',
            name: 'test.jpg'
        );

        $response = $this->sdk->file()->create($request);

        $this->assertCount(2, $this->requestHistory);

        $authRequest = $this->requestHistory[0]['request'];
        $this->assertEquals('POST', $authRequest->getMethod());
        $this->assertEquals('https://auth.test/oauth/token', (string)$authRequest->getUri());

        $fileRequest = $this->requestHistory[1]['request'];
        $this->assertEquals('POST', $fileRequest->getMethod());
        $this->assertEquals('Bearer test_token', $fileRequest->getHeader('Authorization')[0]);
        $this->assertEquals('application/ld+json', $fileRequest->getHeader('Content-Type')[0]);
        $this->assertEquals(
            sprintf('%s/files', $baseUrl),
            (string)$fileRequest->getUri()
        );

        $this->assertEquals('test.jpg', $response->getName());
        $this->assertEquals('https://example.com/test.jpg', $response->getUrl());
        $this->assertEquals($fileId, $response->getUuid()->toString());
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function testDeleteFileFlow(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'access_token' => 'test_token',
            'expires_in' => 3600,
        ])));

        $this->mockHandler->append(new Response(204));

        $fileId = 'e548e809-2ab1-4832-8dd9-f67115da61fb';
        $baseUrl = 'https://api.test/api/v1';

        $request = new FileRequest(
            url: 'https://example.com/test.jpg',
            name: 'test.jpg',
            iri: sprintf('%s/files/%s', $baseUrl, $fileId)
        );

        $this->sdk->file()->delete($request);

        $this->assertCount(2, $this->requestHistory);

        $authRequest = $this->requestHistory[0]['request'];
        $this->assertEquals('POST', $authRequest->getMethod());
        $this->assertEquals('https://auth.test/oauth/token', (string)$authRequest->getUri());

        $fileRequest = $this->requestHistory[1]['request'];
        $this->assertEquals('DELETE', $fileRequest->getMethod());
        $this->assertEquals('Bearer test_token', $fileRequest->getHeader('Authorization')[0]);
        $this->assertEquals(
            sprintf('%s/files/%s', $baseUrl, $fileId),
            (string)$fileRequest->getUri()
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
