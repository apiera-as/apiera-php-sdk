<?php

declare(strict_types=1);

namespace Apiera\Sdk\Enum;

use Apiera\Sdk\DTO\Response\AlternateIdentifier\AlternateIdentifierCollectionResponse;
use Apiera\Sdk\DTO\Response\AlternateIdentifier\AlternateIdentifierResponse;
use Apiera\Sdk\DTO\Response\Attribute\AttributeCollectionResponse;
use Apiera\Sdk\DTO\Response\Attribute\AttributeResponse;
use Apiera\Sdk\DTO\Response\AttributeTerm\AttributeTermCollectionResponse;
use Apiera\Sdk\DTO\Response\AttributeTerm\AttributeTermResponse;
use Apiera\Sdk\DTO\Response\Brand\BrandCollectionResponse;
use Apiera\Sdk\DTO\Response\Brand\BrandResponse;
use Apiera\Sdk\DTO\Response\Category\CategoryCollectionResponse;
use Apiera\Sdk\DTO\Response\Category\CategoryResponse;
use Apiera\Sdk\DTO\Response\Distributor\DistributorCollectionResponse;
use Apiera\Sdk\DTO\Response\Distributor\DistributorResponse;
use Apiera\Sdk\DTO\Response\File\FileCollectionResponse;
use Apiera\Sdk\DTO\Response\File\FileResponse;
use Apiera\Sdk\DTO\Response\Inventory\InventoryCollectionResponse;
use Apiera\Sdk\DTO\Response\Inventory\InventoryResponse;
use Apiera\Sdk\DTO\Response\InventoryLocation\InventoryLocationCollectionResponse;
use Apiera\Sdk\DTO\Response\InventoryLocation\InventoryLocationResponse;
use Apiera\Sdk\DTO\Response\Organization\OrganizationCollectionResponse;
use Apiera\Sdk\DTO\Response\Organization\OrganizationResponse;
use Apiera\Sdk\DTO\Response\Product\ProductCollectionResponse;
use Apiera\Sdk\DTO\Response\Product\ProductResponse;
use Apiera\Sdk\DTO\Response\Property\PropertyCollectionResponse;
use Apiera\Sdk\DTO\Response\Property\PropertyResponse;
use Apiera\Sdk\DTO\Response\PropertyTerm\PropertyTermCollectionResponse;
use Apiera\Sdk\DTO\Response\PropertyTerm\PropertyTermResponse;
use Apiera\Sdk\DTO\Response\ResourceMap\ResourceMapCollectionResponse;
use Apiera\Sdk\DTO\Response\ResourceMap\ResourceMapResponse;
use Apiera\Sdk\DTO\Response\Sku\SkuCollectionResponse;
use Apiera\Sdk\DTO\Response\Sku\SkuResponse;
use Apiera\Sdk\DTO\Response\Store\StoreCollectionResponse;
use Apiera\Sdk\DTO\Response\Store\StoreResponse;
use Apiera\Sdk\DTO\Response\Tag\TagCollectionResponse;
use Apiera\Sdk\DTO\Response\Tag\TagResponse;
use Apiera\Sdk\DTO\Response\Variant\VariantCollectionResponse;
use Apiera\Sdk\DTO\Response\Variant\VariantResponse;
use Exception;
use InvalidArgumentException;

/**
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @since 0.1.0
 */
enum LdType: string
{
    case AlternateIdentifier = 'AlternateIdentifier';
    case Attribute = 'Attribute';
    case AttributeTerm = 'AttributeTerm';
    case Brand = 'Brand';
    case Category = 'Category';
    case Distributor = 'Distributor';
    case File = 'File';
    case Integration = 'Integration';
    case IntegrationResourceMap = 'IntegrationResourceMap';
    case Inventory = 'Inventory';
    case InventoryLocation = 'InventoryLocation';
    case Organization = 'Organization';
    case Product = 'Product';
    case Property = 'Property';
    case PropertyTerm = 'PropertyTerm';
    case Sku = 'Sku';
    case Store = 'Store';
    case Tag = 'Tag';
    case Variant = 'Variant';
    case Collection = 'Collection';

    /**
     * @throws Exception
     */
    public static function getResponseClassForType(self $ldType, ResponseType $responseType): string
    {
        $match = match ($ldType) {
            self::AlternateIdentifier => [
                ResponseType::Single->value => AlternateIdentifierResponse::class,
                ResponseType::Collection->value => AlternateIdentifierCollectionResponse::class,
            ],
            self::Attribute => [
                ResponseType::Single->value => AttributeResponse::class,
                ResponseType::Collection->value => AttributeCollectionResponse::class,
            ],
            self::AttributeTerm => [
                ResponseType::Single->value => AttributeTermResponse::class,
                ResponseType::Collection->value => AttributeTermCollectionResponse::class,
            ],
            self::Brand => [
                ResponseType::Single->value => BrandResponse::class,
                ResponseType::Collection->value => BrandCollectionResponse::class,
            ],
            self::Category => [
                ResponseType::Single->value => CategoryResponse::class,
                ResponseType::Collection->value => CategoryCollectionResponse::class,
            ],
            self::Distributor => [
                ResponseType::Single->value => DistributorResponse::class,
                ResponseType::Collection->value => DistributorCollectionResponse::class,
            ],
            self::File => [
                ResponseType::Single->value => FileResponse::class,
                ResponseType::Collection->value => FileCollectionResponse::class,
            ],
            self::Property => [
                ResponseType::Single->value => PropertyResponse::class,
                ResponseType::Collection->value => PropertyCollectionResponse::class,
            ],
            self::Product => [
                ResponseType::Single->value => ProductResponse::class,
                ResponseType::Collection->value => ProductCollectionResponse::class,
            ],
            self::InventoryLocation => [
                ResponseType::Single->value => InventoryLocationResponse::class,
                ResponseType::Collection->value => InventoryLocationCollectionResponse::class,
            ],
            self::Tag => [
                ResponseType::Single->value => TagResponse::class,
                ResponseType::Collection->value => TagCollectionResponse::class,
            ],
            self::Organization => [
                ResponseType::Single->value => OrganizationResponse::class,
                ResponseType::Collection->value => OrganizationCollectionResponse::class,
            ],
            self::Sku => [
                ResponseType::Single->value => SkuResponse::class,
                ResponseType::Collection->value => SkuCollectionResponse::class,
            ],
            self::Variant => [
                ResponseType::Single->value => VariantResponse::class,
                ResponseType::Collection->value => VariantCollectionResponse::class,
            ],
            self::Store => [
                ResponseType::Single->value => StoreResponse::class,
                ResponseType::Collection->value => StoreCollectionResponse::class,
            ],
            self::PropertyTerm => [
                ResponseType::Single->value => PropertyTermResponse::class,
                ResponseType::Collection->value => PropertyTermCollectionResponse::class,
            ],
            self::Inventory => [
                ResponseType::Single->value => InventoryResponse::class,
                ResponseType::Collection->value => InventoryCollectionResponse::class,
            ],
            self::IntegrationResourceMap => [
                ResponseType::Single->value => ResourceMapResponse::class,
                ResponseType::Collection->value => ResourceMapCollectionResponse::class,
            ],
            self::Integration => throw new Exception('To be implemented'),
            self::Collection => throw new InvalidArgumentException(
                'Collection type cannot be mapped directly'
            ),
        };

        return $match[$responseType->value];
    }
}
