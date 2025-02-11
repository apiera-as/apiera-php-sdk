<?php

declare(strict_types=1);

namespace Tests\Integration\Resource;

use GuzzleHttp\Psr7\Response;

abstract class AbstractTestDeleteOperation extends AbstractTestResourceIntegration
{
    protected string $resourceId = 'e548e809-2ab1-4832-8dd9-f67115da61fb';

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     */
    public function testDeleteFlow(): void
    {
        // Mock auth response
        $this->mockHandler->append(new Response(200, [], json_encode([
            'access_token' => 'test_token',
            'expires_in' => 3600,
        ])));

        // Mock delete response
        $this->mockHandler->append(new Response(204));

        // Execute delete operation
        $this->executeDeleteOperation();

        // Assert requests
        $this->assertCount(2, $this->requestHistory);
        $this->assertAuthRequestValid();
        $this->assertDeleteRequestValid();
    }

    /**
     * @throws \Apiera\Sdk\Exception\Http\ApiException
     * @throws \Apiera\Sdk\Exception\InvalidRequestException
     */
    abstract protected function executeDeleteOperation(): void;

    protected function assertDeleteRequestValid(): void
    {
        $request = $this->requestHistory[1]['request'];
        $this->assertEquals('DELETE', $request->getMethod());
        $this->assertEquals('Bearer test_token', $request->getHeader('Authorization')[0]);
        $this->assertEquals(
            sprintf('%s%s/%s', $this->baseUrl, $this->getResourcePath(), $this->resourceId),
            (string)$request->getUri()
        );
    }
}
