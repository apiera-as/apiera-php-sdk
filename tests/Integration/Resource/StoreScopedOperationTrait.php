<?php

declare(strict_types=1);

namespace Tests\Integration\Resource;

trait StoreScopedOperationTrait
{
    use ResourceOperationTrait;

    protected string $storeId = '9d024f41-3faf-4eef-9d2d-a7e506b81afb';

    abstract protected function getStoreScopedResourcePath(): string;

    protected function getResourcePath(): string
    {
        return $this->normalizePath('stores', $this->storeId, $this->getStoreScopedResourcePath());
    }

    protected function buildStoreUri(string ...$segments): string
    {
        return $this->buildUri('stores', $this->storeId, ...$segments);
    }
}
