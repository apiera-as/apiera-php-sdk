<?php

declare(strict_types=1);

namespace Apiera\Sdk\Interface\DTO;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 0.1.0
 */
interface JsonLDCollectionInterface extends JsonLDInterface
{
    public function getLdContext(): string;

    /**
     * @return array<ResponseInterface>
     */
    public function getMembers(): array;

    public function getTotalItems(): int;

    public function getView(): ?string;

    public function getFirstPage(): ?string;

    public function getLastPage(): ?string;

    public function getNextPage(): ?string;

    public function getPreviousPage(): ?string;
}
