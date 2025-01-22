<?php

declare(strict_types=1);

namespace Apiera\Sdk\Interface\DTO;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @package Apiera\Sdk\Interface\DTO
 * @since 0.1.0
 */
interface JsonLDCollectionInterface extends JsonLDInterface
{
    /**
     * @return string
     */
    public function getLdContext(): string;

    /**
     * @return array<ResponseInterface>
     */
    public function getMembers(): array;

    /**
     * @return int
     */
    public function getTotalItems(): int;

    /**
     * @return string|null
     */
    public function getView(): ?string;

    /**
     * @return string|null
     */
    public function getFirstPage(): ?string;

    /**
     * @return string|null
     */
    public function getLastPage(): ?string;

    /**
     * @return string|null
     */
    public function getNextPage(): ?string;

    /**
     * @return string|null
     */
    public function getPreviousPage(): ?string;
}
