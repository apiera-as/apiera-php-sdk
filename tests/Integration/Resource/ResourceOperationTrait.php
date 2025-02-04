<?php

declare(strict_types=1);

namespace Tests\Integration\Resource;

trait ResourceOperationTrait
{
    protected function normalizePath(string ...$segments): string
    {
        $path = implode('/', array_filter($segments));
        $path = preg_replace('#/+#', '/', $path);

        return '/' . ltrim($path, '/');
    }

    protected function buildUri(string ...$segments): string
    {
        return $this->normalizePath('api/v1', ...$segments);
    }
}
