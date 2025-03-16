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
 * @since 0.1.0
 */
final readonly class QueryParameters
{
    /**
     * @param array<string, string> $filters
     */
    public function __construct(
        private array $filters = [],
        private ?int $page = null,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $params = $this->filters;

        if ($this->page !== null) {
            $params['page'] = $this->page;
        }

        return $params;
    }
}
