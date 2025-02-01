<?php

declare(strict_types=1);

namespace Apiera\Sdk;

use Apiera\Sdk\DataMapper\AlternateIdentifierDataMapper;
use Apiera\Sdk\DataMapper\AttributeDataMapper;
use Apiera\Sdk\DataMapper\AttributeTermDataMapper;
use Apiera\Sdk\DataMapper\BrandDataMapper;
use Apiera\Sdk\DataMapper\CategoryDataMapper;
use Apiera\Sdk\DataMapper\ReflectionAttributeDataMapper;
use Apiera\Sdk\Resource\AlternateIdentifierResource;
use Apiera\Sdk\Resource\AttributeResource;
use Apiera\Sdk\Resource\AttributeTermResource;
use Apiera\Sdk\Resource\BrandResource;
use Apiera\Sdk\Resource\CategoryResource;
use Apiera\Sdk\Resource\DistributorResource;
use Apiera\Sdk\Resource\FileResource;
use Apiera\Sdk\Resource\PropertyResource;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 0.1.0
 */
final readonly class ApieraSdk
{
    private Client $client;

    /**
     * @throws \Apiera\Sdk\Interface\ClientExceptionInterface
     */
    public function __construct(
        private Configuration $configuration,
    ) {
        $this->client = new Client($this->configuration);
    }

    public function category(): CategoryResource
    {
        $dataMapper = new CategoryDataMapper();

        return new CategoryResource($this->client, $dataMapper);
    }

    public function attribute(): AttributeResource
    {
        $dataMapper = new AttributeDataMapper();

        return new AttributeResource($this->client, $dataMapper);
    }

    public function alternateIdentifier(): AlternateIdentifierResource
    {
        $dataMapper = new AlternateIdentifierDataMapper();

        return new AlternateIdentifierResource($this->client, $dataMapper);
    }

    public function brand(): BrandResource
    {
        $dataMapper = new BrandDataMapper();

        return new BrandResource($this->client, $dataMapper);
    }

    public function distributor(): DistributorResource
    {
        $dataMapper = new ReflectionAttributeDataMapper();

        return new DistributorResource($this->client, $dataMapper);
    }

    public function file(): FileResource
    {
        $dataMapper = new ReflectionAttributeDataMapper();

        return new FileResource($this->client, $dataMapper);
    }

    public function attributeTerm(): AttributeTermResource
    {
        $dataMapper = new AttributeTermDataMapper();

        return new AttributeTermResource($this->client, $dataMapper);
    }

    public function property(): PropertyResource
    {
        $dataMapper = new ReflectionAttributeDataMapper();

        return new PropertyResource($this->client, $dataMapper);
    }
}
