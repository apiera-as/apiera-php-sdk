<?php

declare(strict_types=1);

namespace Apiera\Sdk\Exception\Mapping;

use Throwable;

/**
 * Thrown when unable to map response data to DTO
 *
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 1.0.0
 */
final class ResponseMappingException extends MappingException
{
    /**
     * @param array<string, mixed> $responseData
     */
    public function __construct(
        string $message,
        private readonly array $responseData,
        private readonly string $targetClass,
        ?Throwable $previous = null
    ) {
        parent::__construct(
            $message,
            ['responseData' => $responseData, 'targetClass' => $targetClass],
            $previous
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function getResponseData(): array
    {
        return $this->responseData;
    }

    public function getTargetClass(): string
    {
        return $this->targetClass;
    }
}
