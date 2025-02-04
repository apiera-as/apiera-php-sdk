<?php

declare(strict_types=1);

namespace Apiera\Sdk\DTO\Response;

final readonly class PartialCollectionView
{
    public function __construct(
        private string $ldId,
        private string $ldFirst,
        private string $ldLast,
        private ?string $ldNext = null,
        private ?string $ldPrevious = null,
    ) {
    }

    public function getLdId(): string
    {
        return $this->ldId;
    }

    public function getLdFirst(): string
    {
        return $this->ldFirst;
    }

    public function getLdLast(): string
    {
        return $this->ldLast;
    }

    public function getLdNext(): ?string
    {
        return $this->ldNext;
    }

    public function getLdPrevious(): ?string
    {
        return $this->ldPrevious;
    }
}
