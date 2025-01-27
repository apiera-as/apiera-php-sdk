<?php

declare(strict_types=1);

namespace Apiera\Sdk\Interface;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 0.1.0
 */
interface ClientExceptionInterface extends Throwable
{
    public function getRequest(): ?RequestInterface;

    public function getResponse(): ?ResponseInterface;

    public function __toString(): string;
}
