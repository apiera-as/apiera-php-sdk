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
 * @package Apiera\Sdk\Exception
 * @since 1.0.0
 */
class ClientException extends Exception implements ClientExceptionInterface
{
    /**
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     * @param RequestInterface|null $request
     * @param ResponseInterface|null $response
     */
    public function __construct(
        string $message = "",
        int $code = 0,
        ?Throwable $previous = null,
        private readonly ?RequestInterface $request = null,
        private readonly ?ResponseInterface $response = null,
    ) {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return RequestInterface|null
     */
    public function getRequest(): ?RequestInterface
    {
        return $this->request;
    }

    /**
     * @return ResponseInterface|null
     */
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
