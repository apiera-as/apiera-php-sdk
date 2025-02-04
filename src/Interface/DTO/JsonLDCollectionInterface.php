<?php

declare(strict_types=1);

namespace Apiera\Sdk\Interface\DTO;

use Apiera\Sdk\DTO\Response\PartialCollectionView;

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
    public function getLdMembers(): array;

    public function getLdTotalItems(): int;

    public function getLdView(): ?PartialCollectionView;
}
