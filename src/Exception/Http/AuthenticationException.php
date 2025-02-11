<?php

declare(strict_types=1);

namespace Apiera\Sdk\Exception\Http;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

/**
 * 401/403 Authentication/Authorization errors
 *
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 1.0.0
 */
final class AuthenticationException extends ApiException
{
    /**
     * @param array<string, mixed> $context
     */
    public function __construct(
        ?RequestInterface $request = null,
        ?ResponseInterface $response = null,
        ?Throwable $previous = null,
        array $context = []
    ) {
        parent::__construct(
            'Authentication failed',
            $request,
            $response,
            $previous,
            $context
        );
    }
}
