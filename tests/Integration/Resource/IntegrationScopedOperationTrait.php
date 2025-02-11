<?php

declare(strict_types=1);

namespace Tests\Integration\Resource;

trait IntegrationScopedOperationTrait
{
    use ResourceOperationTrait;

    protected string $integrationId = '9d024f41-3faf-4eef-9d2d-a7e506b81afb';

    abstract protected function getIntegrationScopedResourcePath(): string;

    protected function getResourcePath(): string
    {
        return $this->normalizePath('integrations', $this->integrationId, $this->getIntegrationScopedResourcePath());
    }

    protected function buildIntegrationUri(string ...$segments): string
    {
        return $this->buildUri('integrations', $this->integrationId, ...$segments);
    }
}
