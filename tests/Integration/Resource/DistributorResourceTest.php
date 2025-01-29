<?php

declare(strict_types=1);

namespace Tests\Integration\Resource;

use Apiera\Sdk\ApieraSdk;
use Apiera\Sdk\Configuration;
use Apiera\Sdk\DTO\QueryParameters;
use Apiera\Sdk\DTO\Request\Distributor\DistributorRequest;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;

final class DistributorResourceTest extends TestCase
{
    private MockHandler $mockHandler;

    /** @var array<int, array<string, mixed>>|\ArrayAccess<int, array<string, mixed>> */
    private array|\ArrayAccess $requestHistory = [];
    private ApieraSdk $sdk;

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     */
    public function testFindDistributorsFlow(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'access_token' => 'test_token',
            'expires_in' => 3600,
        ])));

        $storeId = '9d024f41-3faf-4eef-9d2d-a7e506b81afb';
        $distributorId = 'e548e809-2ab1-4832-8dd9-f67115da61fb';
        $baseUrl = 'https://api.test/api/v1';

        $distributorData = [
            '@context' => '/api/contexts/Distributor',
            '@id' => '/api/distributors',
            '@type' => 'Collection',
            'member' => [
                [
                    '@id' => sprintf('%s/stores/%s/distributors/%s', $baseUrl, $storeId, $distributorId),
                    '@type' => 'Distributor',
                    'uuid' => $distributorId,
                    'createdAt' => '2024-12-17T09:18:32+00:00',
                    'updatedAt' => '2024-12-17T09:18:32+00:00',
                    'name' => 'Test distributor',
                    'store' => sprintf('%s/stores/%s', $baseUrl, $storeId),
                ],
            ],
            'totalItems' => 1,
        ];
        $this->mockHandler->append(new Response(200, [], json_encode($distributorData)));

        $request = new DistributorRequest(
            name: 'Test distributor',
            store: sprintf('/api/v1/stores/%s', $storeId)
        );

        $response = $this->sdk->distributor()->find($request);

        $this->assertCount(2, $this->requestHistory);

        $authRequest = $this->requestHistory[0]['request'];
        $this->assertEquals('POST', $authRequest->getMethod());
        $this->assertEquals('https://auth.test/oauth/token', (string)$authRequest->getUri());

        $distributorRequest = $this->requestHistory[1]['request'];
        $this->assertEquals('GET', $distributorRequest->getMethod());
        $this->assertEquals('Bearer test_token', $distributorRequest->getHeader('Authorization')[0]);
        $this->assertEquals('application/ld+json', $distributorRequest->getHeader('Content-Type')[0]);
        $this->assertEquals(
            sprintf('%s/stores/%s/distributors', $baseUrl, $storeId),
            (string)$distributorRequest->getUri()
        );

        $this->assertEquals(1, $response->getTotalItems());
        $this->assertCount(1, $response->getMembers());
        $this->assertEquals('Test distributor', $response->getMembers()[0]->getName());
        $this->assertEquals($distributorId, $response->getMembers()[0]->getUuid()->toString());
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     */
    public function testFindOneByDistributorFlow(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'access_token' => 'test_token',
            'expires_in' => 3600,
        ])));

        $storeId = '9d024f41-3faf-4eef-9d2d-a7e506b81afb';
        $distributorId = 'e548e809-2ab1-4832-8dd9-f67115da61fb';
        $baseUrl = 'https://api.test/api/v1';

        $distributorData = [
            '@context' => '/api/contexts/Distributor',
            '@id' => '/api/distributors',
            '@type' => 'Collection',
            'member' => [
                [
                    '@id' => sprintf('%s/stores/%s/distributors/%s', $baseUrl, $storeId, $distributorId),
                    '@type' => 'Distributor',
                    'uuid' => $distributorId,
                    'createdAt' => '2024-12-17T09:18:32+00:00',
                    'updatedAt' => '2024-12-17T09:18:32+00:00',
                    'name' => 'Test distributor',
                    'store' => sprintf('%s/stores/%s', $baseUrl, $storeId),
                ],
            ],
            'totalItems' => 1,
        ];
        $this->mockHandler->append(new Response(200, [], json_encode($distributorData)));

        $request = new DistributorRequest(
            name: 'Test distributor',
            store: sprintf('api/v1/stores/%s', $storeId),
        );

        $params = new QueryParameters(filters: ['name' => 'Test distributor']);
        $response = $this->sdk->distributor()->findOneBy($request, $params);

        $this->assertCount(2, $this->requestHistory);

        $authRequest = $this->requestHistory[0]['request'];
        $this->assertEquals('POST', $authRequest->getMethod());
        $this->assertEquals('https://auth.test/oauth/token', (string)$authRequest->getUri());

        $distributorRequest = $this->requestHistory[1]['request'];
        $this->assertEquals('GET', $distributorRequest->getMethod());
        $this->assertEquals('Bearer test_token', $distributorRequest->getHeader('Authorization')[0]);
        $this->assertEquals('application/ld+json', $distributorRequest->getHeader('Content-Type')[0]);
        $this->assertEquals(
            sprintf(
                '%s/stores/%s/distributors?filters%%5Bname%%5D=%s',
                $baseUrl,
                $storeId,
                rawurlencode('Test distributor')
            ),
            (string)$distributorRequest->getUri()
        );

        $this->assertEquals('Test distributor', $response->getName());
        $this->assertEquals($distributorId, $response->getUuid()->toString());
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     */
    public function testCreateDistributorFlow(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'access_token' => 'test_token',
            'expires_in' => 3600,
        ])));

        $storeId = '9d024f41-3faf-4eef-9d2d-a7e506b81afb';
        $distributorId = 'e548e809-2ab1-4832-8dd9-f67115da61fb';
        $baseUrl = 'https://api.test/api/v1';

        $distributorData = [
            '@id' => sprintf('%s/stores/%s/distributors/%s', $baseUrl, $storeId, $distributorId),
            '@type' => 'Distributor',
            'uuid' => $distributorId,
            'createdAt' => '2024-12-17T09:18:32+00:00',
            'updatedAt' => '2024-12-17T09:18:32+00:00',
            'name' => 'Test distributor',
            'store' => sprintf('%s/stores/%s', $baseUrl, $storeId),
        ];
        $this->mockHandler->append(new Response(201, [], json_encode($distributorData)));

        $request = new DistributorRequest(
            name: 'Test distributor',
            store: sprintf('api/v1/stores/%s', $storeId),
        );

        $response = $this->sdk->distributor()->create($request);

        $this->assertCount(2, $this->requestHistory);

        $authRequest = $this->requestHistory[0]['request'];
        $this->assertEquals('POST', $authRequest->getMethod());
        $this->assertEquals('https://auth.test/oauth/token', (string)$authRequest->getUri());

        $distributorRequest = $this->requestHistory[1]['request'];
        $this->assertEquals('POST', $distributorRequest->getMethod());
        $this->assertEquals('Bearer test_token', $distributorRequest->getHeader('Authorization')[0]);
        $this->assertEquals('application/ld+json', $distributorRequest->getHeader('Content-Type')[0]);
        $this->assertEquals(
            sprintf('%s/stores/%s/distributors', $baseUrl, $storeId),
            (string)$distributorRequest->getUri()
        );

        $this->assertEquals('Test distributor', $response->getName());
        $this->assertEquals($distributorId, $response->getUuid()->toString());
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     */
    public function testUpdateDistributorFlow(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'access_token' => 'test_token',
            'expires_in' => 3600,
        ])));

        $storeId = '9d024f41-3faf-4eef-9d2d-a7e506b81afb';
        $distributorId = 'e548e809-2ab1-4832-8dd9-f67115da61fb';
        $baseUrl = 'https://api.test/api/v1';

        $distributorData = [
            '@id' => sprintf('%s/stores/%s/distributors/%s', $baseUrl, $storeId, $distributorId),
            '@type' => 'Distributor',
            'uuid' => $distributorId,
            'createdAt' => '2024-12-17T09:18:32+00:00',
            'updatedAt' => '2024-12-17T09:18:32+00:00',
            'name' => 'Updated distributor',
            'store' => sprintf('%s/stores/%s', $baseUrl, $storeId),
        ];
        $this->mockHandler->append(new Response(200, [], json_encode($distributorData)));

        $request = new DistributorRequest(
            name: 'Updated distributor',
            iri: sprintf('%s/stores/%s/distributors/%s', $baseUrl, $storeId, $distributorId)
        );

        $response = $this->sdk->distributor()->update($request);

        $this->assertCount(2, $this->requestHistory);

        $authRequest = $this->requestHistory[0]['request'];
        $this->assertEquals('POST', $authRequest->getMethod());
        $this->assertEquals('https://auth.test/oauth/token', (string)$authRequest->getUri());

        $distributorRequest = $this->requestHistory[1]['request'];
        $this->assertEquals('PATCH', $distributorRequest->getMethod());
        $this->assertEquals('Bearer test_token', $distributorRequest->getHeader('Authorization')[0]);
        $this->assertEquals('application/merge-patch+json', $distributorRequest->getHeader('Content-Type')[0]);
        $this->assertEquals(
            sprintf(
                '%s/stores/%s/distributors/%s',
                $baseUrl,
                $storeId,
                $distributorId
            ),
            (string)$distributorRequest->getUri()
        );

        $this->assertEquals('Updated distributor', $response->getName());
        $this->assertEquals($distributorId, $response->getUuid()->toString());
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     */
    public function testDeleteDistributorFlow(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'access_token' => 'test_token',
            'expires_in' => 3600,
        ])));

        $this->mockHandler->append(new Response(204));

        $storeId = '9d024f41-3faf-4eef-9d2d-a7e506b81afb';
        $distributorId = 'e548e809-2ab1-4832-8dd9-f67115da61fb';
        $baseUrl = 'https://api.test/api/v1';

        $request = new DistributorRequest(
            name: 'Test distributor',
            iri: sprintf('%s/stores/%s/distributors/%s', $baseUrl, $storeId, $distributorId)
        );

        $this->sdk->distributor()->delete($request);

        $this->assertCount(2, $this->requestHistory);

        $authRequest = $this->requestHistory[0]['request'];
        $this->assertEquals('POST', $authRequest->getMethod());
        $this->assertEquals('https://auth.test/oauth/token', (string)$authRequest->getUri());

        $distributorRequest = $this->requestHistory[1]['request'];
        $this->assertEquals('DELETE', $distributorRequest->getMethod());
        $this->assertEquals('Bearer test_token', $distributorRequest->getHeader('Authorization')[0]);
        $this->assertEquals(
            sprintf('%s/stores/%s/distributors/%s', $baseUrl, $storeId, $distributorId),
            (string)$distributorRequest->getUri()
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
