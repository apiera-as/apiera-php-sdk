<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Response;

use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Interface\DTO\JsonLDCollectionInterface;
use Apiera\Sdk\Interface\DTO\JsonLDInterface;
use Apiera\Sdk\Interface\DTO\ResponseInterface;

/**
 * @template T of ResponseInterface
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @package Apiera\Sdk\DTO\Response
 * @since 0.1.0
 */
readonly abstract class AbstractCollectionResponse implements JsonLDCollectionInterface
{
    /**
     * @param string $context
     * @param string $id
     * @param LdType $type
     * @param array<T> $members
     * @param int $totalItems
     * @param string|null $view
     * @param string|null $firstPage
     * @param string|null $lastPage
     * @param string|null $nextPage
     * @param string|null $previousPage
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

    /**
     * @return string
     */
    public function getLdContext(): string
    {
        return $this->context;
    }

    /**
     * @return string
     */
    public function getLdId(): string
    {
        return $this->id;
    }

    /**
     * @return LdType
     */
    public function getLdType(): LdType
    {
        return $this->type;
    }

    /**
     * @return array<T>
     */
    public function getMembers(): array
    {
        return $this->members;
    }

    /**
     * @return int
     */
    public function getTotalItems(): int
    {
        return $this->totalItems;
    }

    /**
     * @return string|null
     */
    public function getView(): ?string
    {
        return $this->view;
    }

    /**
     * @return string|null
     */
    public function getFirstPage(): ?string
    {
        return $this->firstPage;
    }

    /**
     * @return string|null
     */
    public function getLastPage(): ?string
    {
        return $this->lastPage;
    }

    /**
     * @return string|null
     */
    public function getNextPage(): ?string
    {
        return $this->nextPage;
    }

    /**
     * @return string|null
     */
    public function getPreviousPage(): ?string
    {
        return $this->previousPage;
    }
}
