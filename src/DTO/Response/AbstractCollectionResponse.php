<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Response;

use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Interface\DTO\JsonLDCollectionInterface;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 0.1.0
 */
readonly abstract class AbstractCollectionResponse implements JsonLDCollectionInterface
{
    /**
     * @param array<\Apiera\Sdk\Interface\DTO\ResponseInterface> $members
     */
    public function __construct(
        private string $context,
        private string $id,
        private LdType $type,
        private array $members = [],
        private int $totalItems = 0,
        private ?string $view = null,
        private ?string $firstPage = null,
        private ?string $lastPage = null,
        private ?string $nextPage = null,
        private ?string $previousPage = null,
    ) {
    }

    public function getLdContext(): string
    {
        return $this->context;
    }

    public function getLdId(): string
    {
        return $this->id;
    }

    public function getLdType(): LdType
    {
        return $this->type;
    }

    /**
     * @return array<\Apiera\Sdk\Interface\DTO\ResponseInterface>
     */
    public function getMembers(): array
    {
        return $this->members;
    }

    public function getTotalItems(): int
    {
        return $this->totalItems;
    }

    public function getView(): ?string
    {
        return $this->view;
    }

    public function getFirstPage(): ?string
    {
        return $this->firstPage;
    }

    public function getLastPage(): ?string
    {
        return $this->lastPage;
    }

    public function getNextPage(): ?string
    {
        return $this->nextPage;
    }

    public function getPreviousPage(): ?string
    {
        return $this->previousPage;
    }
}
