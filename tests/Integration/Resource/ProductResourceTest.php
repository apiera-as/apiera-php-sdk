<?php

declare(strict_types=1);

namespace Tests\Integration\Resource;

use Apiera\Sdk\ApieraSdk;
use Apiera\Sdk\Configuration;
use Apiera\Sdk\DTO\QueryParameters;
use Apiera\Sdk\DTO\Request\Product\ProductRequest;
use Apiera\Sdk\Enum\ProductStatus;
use Apiera\Sdk\Enum\ProductType;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;

final class ProductResourceTest extends TestCase
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
    public function testFindProductsFlow(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'access_token' => 'test_token',
            'expires_in' => 3600,
        ])));

        $storeId = '9d024f41-3faf-4eef-9d2d-a7e506b81afb';
        $productId = 'e548e809-2ab1-4832-8dd9-f67115da61fb';
        $baseUrl = 'https://api.test/api/v1';

        $productData = [
            '@context' => '/api/contexts/Product',
            '@id' => '/api/v1/stores/123/products',
            '@type' => 'Collection',
            'member' => [
                [
                    '@id' => sprintf('%s/stores/%s/products/%s', $baseUrl, $storeId, $productId),
                    '@type' => 'Product',
                    'uuid' => '123e4567-e89b-12d3-a456-426614174000',
                    'createdAt' => '2025-01-01T00:00:00+00:00',
                    'updatedAt' => '2025-01-01T00:00:00+00:00',
                    'type' => 'simple',
                    'status' => 'active',
                    'store' => '/api/v1/stores/123',
                    'sku' => '/api/v1/skus/123',
                    'name' => 'Test Product',
                    'price' => '100.00',
                    'salePrice' => '90.00',
                    'description' => 'Test product description',
                    'shortDescription' => 'Test product short description',
                    'weight' => '100.00',
                    'length' => '100.00',
                    'width' => '100.00',
                    'height' => '100.00',
                    'distributor' => '/api/v1/stores/123/distributors/123',
                    'brand' => '/api/v1/stores/123/brands/123',
                    'image' => '/api/v1/files/123',
                    'categories' => [
                        '/api/v1/stores/123/categories/123',
                        '/api/v1/stores/123/categories/456',
                    ],
                    'tags' => [
                        '/api/v1/stores/123/tags/123',
                        '/api/v1/stores/123/tags/456',
                    ],
                    'attributes' => [
                        '/api/v1/stores/123/attributes/123',
                        '/api/v1/stores/123/attributes/456',
                    ],
                    'images' => [
                        '/api/v1/files/456',
                        '/api/v1/files/789',
                    ],
                    'alternateIdentifiers' => [
                        '/api/v1/alternate_identifiers/123',
                        '/api/v1/alternate_identifiers/456',
                        '/api/v1/alternate_identifiers/789',
                    ],
                    'propertyTerms' => [
                        '/api/v1/stores/123/properties/123/terms/123',
                        '/api/v1/stores/123/properties/456/terms/456',
                    ],
                ],
            ],
            'totalItems' => 1,
        ];
        $this->mockHandler->append(new Response(200, [], json_encode($productData)));

        $request = new ProductRequest(
            type: ProductType::Simple,
            status: ProductStatus::Active,
            sku: '/api/v1/skus/123',
            name: 'Test Product',
            price: '100.00',
            store: sprintf('/api/v1/stores/%s', $storeId)
        );

        $response = $this->sdk->product()->find($request);

        $this->assertCount(2, $this->requestHistory);

        $authRequest = $this->requestHistory[0]['request'];
        $this->assertEquals('POST', $authRequest->getMethod());
        $this->assertEquals('https://auth.test/oauth/token', (string)$authRequest->getUri());

        $productRequest = $this->requestHistory[1]['request'];
        $this->assertEquals('GET', $productRequest->getMethod());
        $this->assertEquals('Bearer test_token', $productRequest->getHeader('Authorization')[0]);
        $this->assertEquals('application/ld+json', $productRequest->getHeader('Content-Type')[0]);
        $this->assertEquals(
            sprintf('%s/stores/%s/products', $baseUrl, $storeId),
            (string)$productRequest->getUri()
        );

        $this->assertEquals(1, $response->getTotalItems());
        $this->assertCount(1, $response->getMembers());
        $this->assertEquals('Test Product', $response->getMembers()[0]->getName());
        $this->assertEquals('100.00', $response->getMembers()[0]->getPrice());
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function testFindOneByProductFlow(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'access_token' => 'test_token',
            'expires_in' => 3600,
        ])));

        $storeId = '9d024f41-3faf-4eef-9d2d-a7e506b81afb';
        $productId = 'e548e809-2ab1-4832-8dd9-f67115da61fb';
        $baseUrl = 'https://api.test/api/v1';

        $productData = [
            '@context' => '/api/contexts/Product',
            '@id' => '/api/v1/stores/123/products',
            '@type' => 'Collection',
            'member' => [
                [
                    '@id' => sprintf('%s/stores/%s/products/%s', $baseUrl, $storeId, $productId),
                    '@type' => 'Product',
                    'uuid' => '123e4567-e89b-12d3-a456-426614174000',
                    'createdAt' => '2025-01-01T00:00:00+00:00',
                    'updatedAt' => '2025-01-01T00:00:00+00:00',
                    'type' => 'simple',
                    'status' => 'active',
                    'store' => '/api/v1/stores/123',
                    'sku' => '/api/v1/skus/123',
                    'name' => 'Test Product',
                    'price' => '100.00',
                    'salePrice' => '90.00',
                    'description' => 'Test product description',
                    'shortDescription' => 'Test product short description',
                    'weight' => '100.00',
                    'length' => '100.00',
                    'width' => '100.00',
                    'height' => '100.00',
                    'distributor' => '/api/v1/stores/123/distributors/123',
                    'brand' => '/api/v1/stores/123/brands/123',
                    'image' => '/api/v1/files/123',
                    'categories' => [
                        '/api/v1/stores/123/categories/123',
                        '/api/v1/stores/123/categories/456',
                    ],
                    'tags' => [
                        '/api/v1/stores/123/tags/123',
                        '/api/v1/stores/123/tags/456',
                    ],
                    'attributes' => [
                        '/api/v1/stores/123/attributes/123',
                        '/api/v1/stores/123/attributes/456',
                    ],
                    'images' => [
                        '/api/v1/files/456',
                        '/api/v1/files/789',
                    ],
                    'alternateIdentifiers' => [
                        '/api/v1/alternate_identifiers/123',
                        '/api/v1/alternate_identifiers/456',
                        '/api/v1/alternate_identifiers/789',
                    ],
                    'propertyTerms' => [
                        '/api/v1/stores/123/properties/123/terms/123',
                        '/api/v1/stores/123/properties/456/terms/456',
                    ],
                ],
            ],
            'totalItems' => 1,
        ];
        $this->mockHandler->append(new Response(200, [], json_encode($productData)));

        $request = new ProductRequest(
            type: ProductType::Simple,
            status: ProductStatus::Active,
            sku: '/api/v1/skus/123',
            store: sprintf('api/v1/stores/%s', $storeId),
        );

        $params = new QueryParameters(filters: ['name' => 'Test Product']);
        $response = $this->sdk->product()->findOneBy($request, $params);

        $this->assertCount(2, $this->requestHistory);

        $authRequest = $this->requestHistory[0]['request'];
        $this->assertEquals('POST', $authRequest->getMethod());
        $this->assertEquals('https://auth.test/oauth/token', (string)$authRequest->getUri());

        $productRequest = $this->requestHistory[1]['request'];
        $this->assertEquals('GET', $productRequest->getMethod());
        $this->assertEquals('Bearer test_token', $productRequest->getHeader('Authorization')[0]);
        $this->assertEquals('application/ld+json', $productRequest->getHeader('Content-Type')[0]);
        $this->assertEquals(
            sprintf(
                '%s/stores/%s/products?filters%%5Bname%%5D=%s',
                $baseUrl,
                $storeId,
                rawurlencode('Test Product')
            ),
            (string)$productRequest->getUri()
        );

        $this->assertEquals('Test Product', $response->getName());
        $this->assertEquals('100.00', $response->getPrice());
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function testCreateProductFlow(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'access_token' => 'test_token',
            'expires_in' => 3600,
        ])));

        $storeId = '9d024f41-3faf-4eef-9d2d-a7e506b81afb';
        $productId = 'e548e809-2ab1-4832-8dd9-f67115da61fb';
        $baseUrl = 'https://api.test/api/v1';

        $productData = [
            '@id' => sprintf('%s/stores/%s/products/%s', $baseUrl, $storeId, $productId),
            '@type' => 'Product',
            'uuid' => '123e4567-e89b-12d3-a456-426614174000',
            'createdAt' => '2025-01-01T00:00:00+00:00',
            'updatedAt' => '2025-01-01T00:00:00+00:00',
            'type' => 'simple',
            'status' => 'active',
            'store' => '/api/v1/stores/123',
            'sku' => '/api/v1/skus/123',
            'name' => 'Test Product',
            'price' => '100.00',
            'salePrice' => '90.00',
            'description' => 'Test product description',
            'shortDescription' => 'Test product short description',
            'weight' => '100.00',
            'length' => '100.00',
            'width' => '100.00',
            'height' => '100.00',
            'distributor' => '/api/v1/stores/123/distributors/123',
            'brand' => '/api/v1/stores/123/brands/123',
            'image' => '/api/v1/files/123',
            'categories' => [
                '/api/v1/stores/123/categories/123',
                '/api/v1/stores/123/categories/456',
            ],
            'tags' => [
                '/api/v1/stores/123/tags/123',
                '/api/v1/stores/123/tags/456',
            ],
            'attributes' => [
                '/api/v1/stores/123/attributes/123',
                '/api/v1/stores/123/attributes/456',
            ],
            'images' => [
                '/api/v1/files/456',
                '/api/v1/files/789',
            ],
            'alternateIdentifiers' => [
                '/api/v1/alternate_identifiers/123',
                '/api/v1/alternate_identifiers/456',
                '/api/v1/alternate_identifiers/789',
            ],
            'propertyTerms' => [
                '/api/v1/stores/123/properties/123/terms/123',
                '/api/v1/stores/123/properties/456/terms/456',
            ],
        ];
        $this->mockHandler->append(new Response(201, [], json_encode($productData)));

        $request = new ProductRequest(
            type: ProductType::Simple,
            status: ProductStatus::Active,
            sku: '/api/v1/skus/123',
            name: 'Test Product',
            price: '99.99',
            store: sprintf('api/v1/stores/%s', $storeId)
        );

        $response = $this->sdk->product()->create($request);

        $this->assertCount(2, $this->requestHistory);

        $authRequest = $this->requestHistory[0]['request'];
        $this->assertEquals('POST', $authRequest->getMethod());
        $this->assertEquals('https://auth.test/oauth/token', (string)$authRequest->getUri());

        $productRequest = $this->requestHistory[1]['request'];
        $this->assertEquals('POST', $productRequest->getMethod());
        $this->assertEquals('Bearer test_token', $productRequest->getHeader('Authorization')[0]);
        $this->assertEquals('application/ld+json', $productRequest->getHeader('Content-Type')[0]);
        $this->assertEquals(
            sprintf('%s/stores/%s/products', $baseUrl, $storeId),
            (string)$productRequest->getUri()
        );

        $this->assertEquals('Test Product', $response->getName());
        $this->assertEquals('100.00', $response->getPrice());
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function testUpdateProductFlow(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'access_token' => 'test_token',
            'expires_in' => 3600,
        ])));

        $storeId = '9d024f41-3faf-4eef-9d2d-a7e506b81afb';
        $productId = 'e548e809-2ab1-4832-8dd9-f67115da61fb';
        $baseUrl = 'https://api.test/api/v1';

        $productData = [
            '@id' => sprintf('%s/stores/%s/products/%s', $baseUrl, $storeId, $productId),
            '@type' => 'Product',
            'uuid' => '123e4567-e89b-12d3-a456-426614174000',
            'createdAt' => '2025-01-01T00:00:00+00:00',
            'updatedAt' => '2025-01-01T00:00:00+00:00',
            'type' => 'simple',
            'status' => 'active',
            'store' => '/api/v1/stores/123',
            'sku' => '/api/v1/skus/123',
            'name' => 'Updated Product',
            'price' => '100.00',
            'salePrice' => '90.00',
            'description' => 'Test product description',
            'shortDescription' => 'Test product short description',
            'weight' => '100.00',
            'length' => '100.00',
            'width' => '100.00',
            'height' => '100.00',
            'distributor' => '/api/v1/stores/123/distributors/123',
            'brand' => '/api/v1/stores/123/brands/123',
            'image' => '/api/v1/files/123',
            'categories' => [
                '/api/v1/stores/123/categories/123',
                '/api/v1/stores/123/categories/456',
            ],
            'tags' => [
                '/api/v1/stores/123/tags/123',
                '/api/v1/stores/123/tags/456',
            ],
            'attributes' => [
                '/api/v1/stores/123/attributes/123',
                '/api/v1/stores/123/attributes/456',
            ],
            'images' => [
                '/api/v1/files/456',
                '/api/v1/files/789',
            ],
            'alternateIdentifiers' => [
                '/api/v1/alternate_identifiers/123',
                '/api/v1/alternate_identifiers/456',
                '/api/v1/alternate_identifiers/789',
            ],
            'propertyTerms' => [
                '/api/v1/stores/123/properties/123/terms/123',
                '/api/v1/stores/123/properties/456/terms/456',
            ],
        ];
        $this->mockHandler->append(new Response(200, [], json_encode($productData)));

        $request = new ProductRequest(
            type: ProductType::Simple,
            status: ProductStatus::Active,
            sku: '/api/v1/skus/123',
            name: 'Updated Product',
            price: '149.99',
            iri: sprintf('%s/stores/%s/products/%s', $baseUrl, $storeId, $productId)
        );

        $response = $this->sdk->product()->update($request);

        $this->assertCount(2, $this->requestHistory);

        $authRequest = $this->requestHistory[0]['request'];
        $this->assertEquals('POST', $authRequest->getMethod());
        $this->assertEquals('https://auth.test/oauth/token', (string)$authRequest->getUri());

        $productRequest = $this->requestHistory[1]['request'];
        $this->assertEquals('PATCH', $productRequest->getMethod());
        $this->assertEquals('Bearer test_token', $productRequest->getHeader('Authorization')[0]);
        $this->assertEquals('application/merge-patch+json', $productRequest->getHeader('Content-Type')[0]);
        $this->assertEquals(
            sprintf(
                '%s/stores/%s/products/%s',
                $baseUrl,
                $storeId,
                $productId
            ),
            (string)$productRequest->getUri()
        );

        $this->assertEquals('Updated Product', $response->getName());
        $this->assertEquals('100.00', $response->getPrice());
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function testDeleteProductFlow(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'access_token' => 'test_token',
            'expires_in' => 3600,
        ])));

        $this->mockHandler->append(new Response(204));

        $storeId = '9d024f41-3faf-4eef-9d2d-a7e506b81afb';
        $productId = 'e548e809-2ab1-4832-8dd9-f67115da61fb';
        $baseUrl = 'https://api.test/api/v1';

        $request = new ProductRequest(
            type: ProductType::Simple,
            status: ProductStatus::Active,
            sku: '/api/v1/skus/123',
            iri: sprintf('%s/stores/%s/products/%s', $baseUrl, $storeId, $productId)
        );

        $this->sdk->product()->delete($request);

        $this->assertCount(2, $this->requestHistory);

        $authRequest = $this->requestHistory[0]['request'];
        $this->assertEquals('POST', $authRequest->getMethod());
        $this->assertEquals('https://auth.test/oauth/token', (string)$authRequest->getUri());

        $productRequest = $this->requestHistory[1]['request'];
        $this->assertEquals('DELETE', $productRequest->getMethod());
        $this->assertEquals('Bearer test_token', $productRequest->getHeader('Authorization')[0]);
        $this->assertEquals(
            sprintf('%s/stores/%s/products/%s', $baseUrl, $storeId, $productId),
            (string)$productRequest->getUri()
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
