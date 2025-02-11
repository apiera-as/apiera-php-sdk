<?php

declare(strict_types=1);

namespace Apiera\Sdk\Exception\Http;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

/**
 * 500 Server errors
 *
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 1.0.0
 */
final class ServerException extends ApiException
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
            'Server error occurred',
            $request,
            $response,
            $previous,
            $context
        );
    }
}
