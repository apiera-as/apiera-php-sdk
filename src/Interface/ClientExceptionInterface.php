<?php

declare(strict_types=1);

namespace Apiera\Sdk\Interface;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @package Apiera\Sdk\Interface
 * @since 0.1.0
 */
interface ClientExceptionInterface extends Throwable
{
    /**
     * @return RequestInterface|null
     */
    public function getRequest(): ?RequestInterface;

    /**
     * @return ResponseInterface|null
     */
    public function getResponse(): ?ResponseInterface;

    /**
     * @return string
     */
    public function __toString(): string;
}
