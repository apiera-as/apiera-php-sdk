<?php

declare(strict_types=1);

namespace Tests\Integration\Resource;

use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Interface\DTO\ResponseInterface;
use GuzzleHttp\Psr7\Response;

abstract class AbstractTestCreateOperation extends AbstractTestResourceIntegration
{
    protected const string CREATED_AT = '2024-12-17T09:18:32+00:00';
    protected const string UPDATED_AT = '2024-12-17T09:18:32+00:00';

    protected string $resourceId = 'e548e809-2ab1-4832-8dd9-f67115da61fb';

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    public function testCreateFlow(): void
    {
        // Mock auth response
        $this->mockHandler->append(new Response(200, [], json_encode([
            'access_token' => 'test_token',
            'expires_in' => 3600,
        ])));

        // Mock create response
        $this->mockHandler->append(
            new Response(201, [], json_encode($this->getMockResponseData()))
        );

        // Execute create operation
        $response = $this->executeCreateOperation();

        // Assert requests
        $this->assertCount(2, $this->requestHistory);
        $this->assertAuthRequestValid();
        $this->assertCreateRequestValid();

        // Assert response
        $this->assertResponseValid($response);
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     * @throws \Apiera\Sdk\Exception\Mapping\MappingException
     */
    abstract protected function executeCreateOperation(): ResponseInterface;

    /**
     * @return array<string, mixed>
     */
    abstract protected function getMockResponseData(): array;

    /**
     * @return class-string<ResponseInterface>
     */
    abstract protected function getResponseClass(): string;

    abstract protected function assertResourceSpecificFields(ResponseInterface $response): void;

    protected function assertResponseValid(ResponseInterface $response): void
    {
        $this->assertInstanceOf($this->getResponseClass(), $response);
        $this->assertEquals(sprintf('/api/v1%s/%s', $this->getResourcePath(), $this->resourceId), $response->getLdId());
        $this->assertEquals(LdType::from($this->getResourceType()), $response->getLdType());
        $this->assertEquals(self::CREATED_AT, $response->getCreatedAt()->format('Y-m-d\TH:i:sP'));
        $this->assertEquals(self::UPDATED_AT, $response->getUpdatedAt()->format('Y-m-d\TH:i:sP'));
        $this->assertEquals($this->resourceId, $response->getUuid()->toString());

        $this->assertResourceSpecificFields($response);
    }

    protected function assertCreateRequestValid(): void
    {
        $request = $this->requestHistory[1]['request'];
        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('Bearer test_token', $request->getHeader('Authorization')[0]);
        $this->assertEquals('application/ld+json', $request->getHeader('Content-Type')[0]);
        $this->assertEquals(
            sprintf('%s%s', $this->baseUrl, $this->getResourcePath()),
            (string)$request->getUri()
        );
    }
}
