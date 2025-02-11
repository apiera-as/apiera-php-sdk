<?php

declare(strict_types=1);

namespace Apiera\Sdk\Factory;

use Apiera\Sdk\Exception\Http\ApiException;
use Apiera\Sdk\Exception\Http\AuthenticationException;
use Apiera\Sdk\Exception\Http\BadRequestException;
use Apiera\Sdk\Exception\Http\GenericHttpException;
use Apiera\Sdk\Exception\Http\NotFoundException;
use Apiera\Sdk\Exception\Http\ServerException;
use Apiera\Sdk\Exception\Http\UnprocessableEntityException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 1.0.0
 */
final class ApiExceptionFactory
{
    public static function createFromResponse(
        string $message,
        ?ResponseInterface $response = null,
        ?RequestInterface $request = null,
        ?Throwable $previous = null,
    ): ApiException {
        if ($response === null) {
            // No response means network error or similar
            return new GenericHttpException($message, $request, null, $previous);
        }

        $statusCode = $response->getStatusCode();

        return match ($statusCode) {
            400 => new BadRequestException(
                $request,
                $response,
                $previous
            ),
            401, 403 => new AuthenticationException(
                $request,
                $response,
                $previous
            ),
            404 => new NotFoundException(
                'unknown',
                'unknown',
                $request,
                $response,
                $previous
            ),
            422 => new UnprocessableEntityException(
                self::extractViolations($response),
                $request,
                $response,
                $previous
            ),
            500, 502, 503, 504 => new ServerException($request, $response, $previous),
            default => new GenericHttpException(
                $message,
                $request,
                $response,
                $previous,
                ['status_code' => $statusCode]
            )
        };
    }

    /**
     * @return array<string, array<string>>
     */
    private static function extractViolations(ResponseInterface $response): array
    {
        try {
            $data = json_decode($response->getBody()->getContents(), true);

            return $data['violations'] ?? [];
        } catch (Throwable) {
            return [];
        }
    }
}
