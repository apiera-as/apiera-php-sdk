<?php

declare(strict_types=1);

namespace Apiera\Sdk;

use Apiera\Sdk\DataMapper\ReflectionAttributeDataMapper;
use Apiera\Sdk\Factory\ResourceFactory;
use Apiera\Sdk\Interface\RequestResourceInterface;
use Apiera\Sdk\Resource\AlternateIdentifierResource;
use Apiera\Sdk\Resource\AttributeResource;
use Apiera\Sdk\Resource\AttributeTermResource;
use Apiera\Sdk\Resource\BrandResource;
use Apiera\Sdk\Resource\CategoryResource;
use Apiera\Sdk\Resource\DistributorResource;
use Apiera\Sdk\Resource\FileResource;
use Apiera\Sdk\Resource\IntegrationResource;
use Apiera\Sdk\Resource\InventoryLocationResource;
use Apiera\Sdk\Resource\InventoryResource;
use Apiera\Sdk\Resource\OrganizationResource;
use Apiera\Sdk\Resource\ProductResource;
use Apiera\Sdk\Resource\PropertyResource;
use Apiera\Sdk\Resource\PropertyTermResource;
use Apiera\Sdk\Resource\ResourceMapResource;
use Apiera\Sdk\Resource\SkuResource;
use Apiera\Sdk\Resource\StoreResource;
use Apiera\Sdk\Resource\TagResource;
use Apiera\Sdk\Resource\VariantResource;
use BadMethodCallException;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 0.1.0
 *
 * @method CategoryResource category()
 * @method AttributeResource attribute()
 * @method AlternateIdentifierResource alternateIdentifier()
 * @method BrandResource brand()
 * @method DistributorResource distributor()
 * @method FileResource file()
 * @method AttributeTermResource attributeTerm()
 * @method PropertyResource property()
 * @method ProductResource product()
 * @method InventoryLocationResource inventoryLocation()
 * @method OrganizationResource organization()
 * @method SkuResource sku()
 * @method VariantResource variant()
 * @method TagResource tag()
 * @method StoreResource store()
 * @method PropertyTermResource propertyTerm()
 * @method ResourceMapResource resourceMap()
 * @method InventoryResource inventory()
 * @method IntegrationResource integration()
 */
final readonly class ApieraSdk
{
    /** @var array<string, class-string<RequestResourceInterface>> */
    private const array REQUEST_RESOURCE_MAP = [
        'category' => CategoryResource::class,
        'attribute' => AttributeResource::class,
        'alternateIdentifier' => AlternateIdentifierResource::class,
        'brand' => BrandResource::class,
        'distributor' => DistributorResource::class,
        'file' => FileResource::class,
        'attributeTerm' => AttributeTermResource::class,
        'property' => PropertyResource::class,
        'product' => ProductResource::class,
        'inventoryLocation' => InventoryLocationResource::class,
        'organization' => OrganizationResource::class,
        'sku' => SkuResource::class,
        'variant' => VariantResource::class,
        'tag' => TagResource::class,
        'store' => StoreResource::class,
        'propertyTerm' => PropertyTermResource::class,
        'resourceMap' => ResourceMapResource::class,
        'inventory' => InventoryResource::class,
        'integration' => IntegrationResource::class,
    ];

    private ResourceFactory $resourceFactory;

    /**
     * @throws \Apiera\Sdk\Exception\ConfigurationException
     */
    public function __construct(
        private Configuration $configuration,
    ) {
        $this->resourceFactory = new ResourceFactory(
            new Client($this->configuration),
            new ReflectionAttributeDataMapper(),
            $this->configuration,
        );
    }

    /**
     * @param class-string<T> $resourceClass
     *
     * @return T
     *
     * @template T of RequestResourceInterface
     */
    public function resource(string $resourceClass): RequestResourceInterface
    {
        return $this->resourceFactory->create($resourceClass);
    }

    /**
     * @param array<array-key, mixed> $arguments
     */
    public function __call(string $name, array $arguments): RequestResourceInterface
    {
        if (!isset(self::REQUEST_RESOURCE_MAP[$name])) {
            throw new BadMethodCallException(sprintf('Method "%s" does not exist', $name));
        }

        return $this->resource(self::REQUEST_RESOURCE_MAP[$name]);
    }
}
