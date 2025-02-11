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
     * @param array<\Apiera\Sdk\Interface\DTO\ResponseInterface> $ldMembers
     */
    public function __construct(
        private string $ldContext,
        private string $ldId,
        private LdType $ldType,
        private array $ldMembers = [],
        private int $ldTotalItems = 0,
        private ?PartialCollectionView $ldView = null,
    ) {
    }

    public function getLdContext(): string
    {
        return $this->ldContext;
    }

    public function getLdId(): string
    {
        return $this->ldId;
    }

    public function getLdType(): LdType
    {
        return $this->ldType;
    }

    /**
     * @return array<\Apiera\Sdk\Interface\DTO\ResponseInterface>
     */
    public function getLdMembers(): array
    {
        return $this->ldMembers;
    }

    public function getLdTotalItems(): int
    {
        return $this->ldTotalItems;
    }

    public function getLdView(): ?PartialCollectionView
    {
        return $this->ldView;
    }
}
