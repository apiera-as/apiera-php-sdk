<?php

declare(strict_types=1);

namespace Apiera\Sdk\Exception\Http;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

/**
 * Used for HTTP errors that don't fit into other specific categories
 *
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 1.0.0
 */
final class GenericHttpException extends ApiException
{
    /**
     * @param array<string, mixed> $context
     */
    public function __construct(
        string $message,
        ?RequestInterface $request = null,
        ?ResponseInterface $response = null,
        ?Throwable $previous = null,
        array $context = []
    ) {
        parent::__construct(
            $message,
            $request,
            $response,
            $previous,
            $context
        );
    }
}
