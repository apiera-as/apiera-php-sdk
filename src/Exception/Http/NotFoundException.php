<?php

declare(strict_types=1);

namespace Apiera\Sdk\Exception\Http;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

/**
 * 404 Not Found errors
 *
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 0.3.0
 */
final class NotFoundException extends ApiException
{
    public function __construct(
        string $resourceType,
        string $identifier,
        ?RequestInterface $request = null,
        ?ResponseInterface $response = null,
        ?Throwable $previous = null
    ) {
        parent::__construct(
            sprintf(
                'Resource of type "%s" with identifier "%s" not found',
                $resourceType,
                $identifier
            ),
            $request,
            $response,
            $previous,
            [
                'resourceType' => $resourceType,
                'identifier' => $identifier,
            ]
        );
    }
}
