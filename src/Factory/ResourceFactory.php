<?php

declare(strict_types=1);

namespace Apiera\Sdk\Factory;

use Apiera\Sdk\Interface\ClientInterface;
use Apiera\Sdk\Interface\DataMapperInterface;
use Apiera\Sdk\Interface\RequestResourceInterface;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 0.3.0
 */
final readonly class ResourceFactory
{
    public function __construct(
        private ClientInterface $client,
        private DataMapperInterface $dataMapper,
    ) {
    }

    /**
     * @param class-string<T> $resourceClass
     *
     * @return T
     *
     * @template T of RequestResourceInterface
     */
    public function create(string $resourceClass): RequestResourceInterface
    {
        return new $resourceClass($this->client, $this->dataMapper);
    }
}
