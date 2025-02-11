<?php

declare(strict_types=1);

namespace Apiera\Sdk\Exception\Http;

use Exception;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

/**
 * Base exception for all HTTP/API related errors
 *
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 1.0.0
 */
abstract class ApiException extends Exception
{
    /**
     * @param array<string, mixed> $context
     */
    public function __construct(
        string $message,
        private readonly ?RequestInterface $request = null,
        private readonly ?ResponseInterface $response = null,
        ?Throwable $previous = null,
        private readonly array $context = []
    ) {
        parent::__construct($message, 0, $previous);
    }

    public function getRequest(): ?RequestInterface
    {
        return $this->request;
    }

    public function getResponse(): ?ResponseInterface
    {
        return $this->response;
    }

    /**
     * @return array<string, mixed>
     */
    public function getContext(): array
    {
        return $this->context;
    }

    public function __toString(): string
    {
        $message = parent::__toString();

        if ($this->request) {
            $message .= "\nRequest URI: " . $this->request->getUri();
            $message .= "\nRequest Method: " . $this->request->getMethod();
        }

        if ($this->response) {
            $message .= "\nResponse Status: " . $this->response->getStatusCode();
            $message .= "\nResponse Body: " . $this->response->getBody()->getContents();
        }

        return $message;
    }
}
