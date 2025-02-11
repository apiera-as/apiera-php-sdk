<?php

declare(strict_types=1);

namespace Apiera\Sdk\Exception\Mapping;

use Apiera\Sdk\Interface\DTO\RequestInterface;
use Throwable;

/**
 * Thrown when unable to map request DTO to API data
 *
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 1.0.0
 */
final class RequestMappingException extends MappingException
{
    public function __construct(
        string $message,
        private readonly RequestInterface $request,
        ?Throwable $previous = null
    ) {
        parent::__construct(
            $message,
            ['requestClass' => $request::class],
            $previous
        );
    }

    public function getRequest(): RequestInterface
    {
        return $this->request;
    }
}
