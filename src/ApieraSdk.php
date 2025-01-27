<?php

declare(strict_types=1);

namespace Apiera\Sdk;

use Apiera\Sdk\DataMapper\AlternateIdentifierDataMapper;
use Apiera\Sdk\DataMapper\AttributeDataMapper;
use Apiera\Sdk\DataMapper\CategoryDataMapper;
use Apiera\Sdk\Exception\ClientException;
use Apiera\Sdk\Resource\AlternateIdentifierResource;
use Apiera\Sdk\Resource\AttributeResource;
use Apiera\Sdk\Resource\CategoryResource;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @package Apiera\Sdk
 * @since 0.1.0
 */
final readonly class ApieraSdk
{
    private Client $client;

    /**
     * @param Configuration $configuration
     * @throws ClientException
     */
    public function __construct(
        private Configuration $configuration,
    ) {
        $this->client = new Client($this->configuration);
    }

    /**
     * @return CategoryResource
     */
    public function category(): CategoryResource
    {
        $dataMapper = new CategoryDataMapper();
        return new CategoryResource($this->client, $dataMapper);
    }

    /**
     * @return AttributeResource
     */
    public function attribute(): AttributeResource
    {
        $dataMapper = new AttributeDataMapper();
        return new AttributeResource($this->client, $dataMapper);
    }

    /**
     * @return AlternateIdentifierResource
     */
    public function alternateIdentifier(): AlternateIdentifierResource
    {
        $dataMapper = new AlternateIdentifierDataMapper();
        return new AlternateIdentifierResource($this->client, $dataMapper);
    }
}
