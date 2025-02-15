<?php

declare(strict_types=1);

namespace Apiera\Sdk\Interface;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 2.0.0
 */
interface ContextRequestResourceInterface extends RequestResourceInterface
{
    public function __construct(
        ClientInterface $client,
        DataMapperInterface $dataMapper,
        ConfigurationInterface $configuration
    );
}
