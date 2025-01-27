<?php

declare(strict_types=1);

namespace Apiera\Sdk\Enum;

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
}
