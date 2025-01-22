<?php

declare(strict_types=1);

namespace Apiera\Sdk\Enum;

/**
 * Enumeration of available JSON-LD resource types.
 *
 * Defines all supported resource types in the API. Used for type-safe handling
 * of different resources and their responses.
 *
 * @author Fredrik Tveraaen <fredrik.tveraaen@apiera.io>
 * @package Apiera\Sdk\Enum
 *
 * @see AbstractResponse Uses these types for resource identification
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
