<?php

declare(strict_types=1);

namespace Apiera\Sdk\Exception\Mapping;

use Exception;
use Throwable;

/**
 * Base exception for all mapping-related errors
 *
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 0.3.0
 */
abstract class MappingException extends Exception
{
    /**
     * @param array<string, mixed> $context
     */
    public function __construct(
        string $message,
        private readonly array $context = [],
        ?Throwable $previous = null
    ) {
        parent::__construct($message, 0, $previous);
    }

    /**
     * @return array<string, mixed>
     */
    public function getContext(): array
    {
        return $this->context;
    }
}
