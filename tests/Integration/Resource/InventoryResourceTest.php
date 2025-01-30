<?php

declare(strict_types=1);

namespace Tests\Integration\Resource;

use Apiera\Sdk\ApieraSdk;
use Apiera\Sdk\Configuration;
use Apiera\Sdk\DTO\QueryParameters;
use Apiera\Sdk\DTO\Request\Inventory\InventoryRequest;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;

final class InventoryResourceTest extends TestCase
{
    private MockHandler $mockHandler;

    /** @var array<int, array<string, mixed>>|\ArrayAccess<int, array<string, mixed>> */
    private array|\ArrayAccess $requestHistory = [];
    private ApieraSdk $sdk;

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     */
    public function testFindInventoriesFlow(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'access_token' => 'test_token',
            'expires_in' => 3600,
        ])));

        $inventoryLocationId = '9d024f41-3faf-4eef-9d2d-a7e506b81afb';
        $inventoryId = 'e548e809-2ab1-4832-8dd9-f67115da61fb';
        $baseUrl = 'https://api.test/api/v1';

        $inventoryData = [
            '@context' => '/api/contexts/Inventory',
            '@id' => '/api/inventories',
            '@type' => 'Collection',
            'member' => [
                [
                    '@id' => sprintf(
                        '%s/inventory_locations/%s/inventories/%s',
                        $baseUrl,
                        $inventoryLocationId,
                        $inventoryId
                    ),
                    '@type' => 'Inventory',
                    'uuid' => $inventoryId,
                    'createdAt' => '2024-12-17T09:18:32+00:00',
                    'updatedAt' => '2024-12-17T09:18:32+00:00',
                    'quantity' => 10,
                    'sku' => sprintf('%s/skus/123', $baseUrl),
                    'inventoryLocation' => sprintf('%s/inventory_locations/%s', $baseUrl, $inventoryLocationId),
                ]
            ],
            'totalItems' => 1,
        ];
        $this->mockHandler->append(new Response(200, [], json_encode($inventoryData)));

        $request = new InventoryRequest(
            quantity: 10,
            sku: sprintf('/api/v1/skus/123'),
            inventoryLocation: sprintf('/api/v1/inventory_locations/%s', $inventoryLocationId)
        );

        $response = $this->sdk->inventory()->find($request);

        $this->assertCount(2, $this->requestHistory);

        $authRequest = $this->requestHistory[0]['request'];
        $this->assertEquals('POST', $authRequest->getMethod());
        $this->assertEquals('https://auth.test/oauth/token', (string)$authRequest->getUri());

        $inventoryRequest = $this->requestHistory[1]['request'];
        $this->assertEquals('GET', $inventoryRequest->getMethod());
        $this->assertEquals('Bearer test_token', $inventoryRequest->getHeader('Authorization')[0]);
        $this->assertEquals('application/ld+json', $inventoryRequest->getHeader('Content-Type')[0]);
        $this->assertEquals(
            sprintf('%s/inventory_locations/%s/inventories', $baseUrl, $inventoryLocationId),
            (string)$inventoryRequest->getUri()
        );

        $this->assertEquals(1, $response->getTotalItems());
        $this->assertCount(1, $response->getMembers());
        $this->assertEquals(10, $response->getMembers()[0]->getQuantity());
        $this->assertEquals($inventoryId, $response->getMembers()[0]->getUuid()->toString());
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     */
    public function testFindOneByInventoryFlow(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'access_token' => 'test_token',
            'expires_in' => 3600,
        ])));

        $inventoryLocationId = '9d024f41-3faf-4eef-9d2d-a7e506b81afb';
        $inventoryId = 'e548e809-2ab1-4832-8dd9-f67115da61fb';
        $baseUrl = 'https://api.test/api/v1';

        $inventoryData = [
            '@context' => '/api/contexts/Inventory',
            '@id' => '/api/inventories',
            '@type' => 'Collection',
            'member' => [
                [
                    '@id' => sprintf(
                        '%s/inventory_locations/%s/inventories/%s',
                        $baseUrl,
                        $inventoryLocationId,
                        $inventoryId
                    ),
                    '@type' => 'Inventory',
                    'uuid' => $inventoryId,
                    'createdAt' => '2024-12-17T09:18:32+00:00',
                    'updatedAt' => '2024-12-17T09:18:32+00:00',
                    'quantity' => 10,
                    'sku' => sprintf('%s/skus/123', $baseUrl),
                    'inventoryLocation' => sprintf('%s/inventory_locations/%s', $baseUrl, $inventoryLocationId),
                ]
            ],
            'totalItems' => 1,
        ];
        $this->mockHandler->append(new Response(200, [], json_encode($inventoryData)));

        $request = new InventoryRequest(
            quantity: 10,
            sku: sprintf('/api/v1/skus/123'),
            inventoryLocation: sprintf('/api/v1/inventory_locations/%s', $inventoryLocationId)
        );

        $params = new QueryParameters(filters: ['sku' => '/api/v1/skus/123']);
        $response = $this->sdk->inventory()->findOneBy($request, $params);

        $this->assertCount(2, $this->requestHistory);

        $authRequest = $this->requestHistory[0]['request'];
        $this->assertEquals('POST', $authRequest->getMethod());
        $this->assertEquals('https://auth.test/oauth/token', (string)$authRequest->getUri());

        $inventoryRequest = $this->requestHistory[1]['request'];
        $this->assertEquals('GET', $inventoryRequest->getMethod());
        $this->assertEquals('Bearer test_token', $inventoryRequest->getHeader('Authorization')[0]);
        $this->assertEquals('application/ld+json', $inventoryRequest->getHeader('Content-Type')[0]);
        $this->assertEquals(
            sprintf(
                '%s/inventory_locations/%s/inventories?filters%%5Bsku%%5D=%s',
                $baseUrl,
                $inventoryLocationId,
                rawurlencode('/api/v1/skus/123')
            ),
            (string)$inventoryRequest->getUri()
        );

        $this->assertEquals(10, $response->getQuantity());
        $this->assertEquals($inventoryId, $response->getUuid()->toString());
        $this->assertEquals(sprintf('/api/v1/skus/123'), $response->getSku());
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     */
    public function testCreateInventoryFlow(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'access_token' => 'test_token',
            'expires_in' => 3600,
        ])));

        $inventoryLocationId = '9d024f41-3faf-4eef-9d2d-a7e506b81afb';
        $inventoryId = 'e548e809-2ab1-4832-8dd9-f67115da61fb';
        $baseUrl = 'https://api.test/api/v1';

        $inventoryData = [
            '@id' => sprintf('%s/inventory_locations/%s/inventories/%s', $baseUrl, $inventoryLocationId, $inventoryId),
            '@type' => 'Inventory',
            'uuid' => $inventoryId,
            'createdAt' => '2024-12-17T09:18:32+00:00',
            'updatedAt' => '2024-12-17T09:18:32+00:00',
            'quantity' => 10,
            'sku' => sprintf('%s/skus/123', $baseUrl),
            'inventoryLocation' => sprintf('%s/inventory_locations/%s', $baseUrl, $inventoryLocationId),
        ];
        $this->mockHandler->append(new Response(201, [], json_encode($inventoryData)));

        $request = new InventoryRequest(
            quantity: 10,
            sku: sprintf('/api/v1/skus/123'),
            inventoryLocation: sprintf('/api/v1/inventory_locations/%s', $inventoryLocationId)
        );

        $response = $this->sdk->inventory()->create($request);

        $this->assertCount(2, $this->requestHistory);

        $authRequest = $this->requestHistory[0]['request'];
        $this->assertEquals('POST', $authRequest->getMethod());
        $this->assertEquals('https://auth.test/oauth/token', (string)$authRequest->getUri());

        $inventoryRequest = $this->requestHistory[1]['request'];
        $this->assertEquals('POST', $inventoryRequest->getMethod());
        $this->assertEquals('Bearer test_token', $inventoryRequest->getHeader('Authorization')[0]);
        $this->assertEquals('application/ld+json', $inventoryRequest->getHeader('Content-Type')[0]);
        $this->assertEquals(
            sprintf('%s/inventory_locations/%s/inventories', $baseUrl, $inventoryLocationId),
            (string)$inventoryRequest->getUri()
        );

        $this->assertEquals(10, $response->getQuantity());
        $this->assertEquals($inventoryId, $response->getUuid()->toString());
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     */
    public function testUpdateInventoryFlow(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'access_token' => 'test_token',
            'expires_in' => 3600,
        ])));

        $inventoryLocationId = '9d024f41-3faf-4eef-9d2d-a7e506b81afb';
        $inventoryId = 'e548e809-2ab1-4832-8dd9-f67115da61fb';
        $baseUrl = 'https://api.test/api/v1';

        $inventoryData = [
            '@id' => sprintf('%s/inventory_locations/%s/inventories/%s', $baseUrl, $inventoryLocationId, $inventoryId),
            '@type' => 'Inventory',
            'uuid' => $inventoryId,
            'createdAt' => '2024-12-17T09:18:32+00:00',
            'updatedAt' => '2024-12-17T09:18:32+00:00',
            'quantity' => 15,
            'sku' => sprintf('%s/skus/123', $baseUrl),
            'inventoryLocation' => sprintf('%s/inventory_locations/%s', $baseUrl, $inventoryLocationId),
        ];
        $this->mockHandler->append(new Response(200, [], json_encode($inventoryData)));

        $request = new InventoryRequest(
            quantity: 15,
            sku: sprintf('/api/v1/skus/123'),
            iri: sprintf(
                '%s/inventory_locations/%s/inventories/%s',
                $baseUrl,
                $inventoryLocationId,
                $inventoryId
            )
        );

        $response = $this->sdk->inventory()->update($request);

        $this->assertCount(2, $this->requestHistory);

        $authRequest = $this->requestHistory[0]['request'];
        $this->assertEquals('POST', $authRequest->getMethod());
        $this->assertEquals('https://auth.test/oauth/token', (string)$authRequest->getUri());

        $inventoryRequest = $this->requestHistory[1]['request'];
        $this->assertEquals('PATCH', $inventoryRequest->getMethod());
        $this->assertEquals('Bearer test_token', $inventoryRequest->getHeader('Authorization')[0]);
        $this->assertEquals('application/merge-patch+json', $inventoryRequest->getHeader('Content-Type')[0]);
        $this->assertEquals(
            sprintf(
                '%s/inventory_locations/%s/inventories/%s',
                $baseUrl,
                $inventoryLocationId,
                $inventoryId
            ),
            (string)$inventoryRequest->getUri()
        );

        $this->assertEquals(15, $response->getQuantity());
        $this->assertEquals($inventoryId, $response->getUuid()->toString());
    }

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     */
    public function testDeleteInventoryFlow(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'access_token' => 'test_token',
            'expires_in' => 3600,
        ])));

        $this->mockHandler->append(new Response(204));

        $inventoryLocationId = '9d024f41-3faf-4eef-9d2d-a7e506b81afb';
        $inventoryId = 'e548e809-2ab1-4832-8dd9-f67115da61fb';
        $baseUrl = 'https://api.test/api/v1';

        $request = new InventoryRequest(
            quantity: 10,
            sku: sprintf('/api/v1/skus/123'),
            iri: sprintf(
                '%s/inventory_locations/%s/inventories/%s',
                $baseUrl,
                $inventoryLocationId,
                $inventoryId
            )
        );

        $this->sdk->inventory()->delete($request);

        $this->assertCount(2, $this->requestHistory);

        $authRequest = $this->requestHistory[0]['request'];
        $this->assertEquals('POST', $authRequest->getMethod());
        $this->assertEquals('https://auth.test/oauth/token', (string)$authRequest->getUri());

        $inventoryRequest = $this->requestHistory[1]['request'];
        $this->assertEquals('DELETE', $inventoryRequest->getMethod());
        $this->assertEquals('Bearer test_token', $inventoryRequest->getHeader('Authorization')[0]);
        $this->assertEquals(
            sprintf('%s/inventory_locations/%s/inventories/%s', $baseUrl, $inventoryLocationId, $inventoryId),
            (string)$inventoryRequest->getUri()
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
