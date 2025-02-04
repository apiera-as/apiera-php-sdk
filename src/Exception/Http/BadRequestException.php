<?php

declare(strict_types=1);

namespace Apiera\Sdk\Exception\Http;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

/**
 * 400 Bad Request errors
 *
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 0.3.0
 */
final class BadRequestException extends ApiException
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
            'Bad request',
            $request,
            $response,
            $previous,
            $context
        );
    }
}
