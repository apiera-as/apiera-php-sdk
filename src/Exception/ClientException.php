<?php

declare(strict_types=1);

namespace Apiera\Sdk\Exception;

use Apiera\Sdk\Interface\ClientExceptionInterface;
use Exception;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

/**
 * Exception thrown for API client errors.
 *
 * Provides detailed information about failed API requests including the original
 * request, response (if available), and underlying error details.
 *
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 0.1.0
 */
final class ClientException extends Exception implements ClientExceptionInterface
{
    public function __construct(
        string $message = "",
        int $code = 0,
        ?Throwable $previous = null,
        private readonly ?RequestInterface $request = null,
        private readonly ?ResponseInterface $response = null,
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getRequest(): ?RequestInterface
    {
        return $this->request;
    }

    public function getResponse(): ?ResponseInterface
    {
        return $this->response;
    }

    public function __toString(): string
    {
        $message = parent::__toString();

        if ($this->request) {
            $message .= "\nRequest: " . $this->request->getUri();
        }

        if ($this->response) {
            $message .= "\nResponse Status: " . $this->response->getStatusCode();
        }

        return $message;
    }
}
