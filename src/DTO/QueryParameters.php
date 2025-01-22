<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO;

/**
 * Data Transfer Object for API query parameters.
 *
 * Encapsulates query parameters for API requests including filtering, pagination,
 * and general parameters. All parameters are immutable after construction.
 *
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @package Apiera\Sdk\DTO
 * @since 0.1.0
 */
readonly class QueryParameters
{
    /**
     * @param array<string, scalar|array<scalar>> $params
     * @param array<string, string> $filters
     * @param int|null $page
     */
    public function __construct(
        private array $params = [],
        private array $filters = [],
        private ?int $page = null,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'params' => $this->params,
            'filters' => $this->filters,
            'page' => $this->page,
        ], fn($value) => $value !== null && $value !== []);
    }
}
