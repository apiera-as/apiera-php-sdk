# Apiera SDK Documentation examples

## Table of Contents

1. [Examples](#examples)
    - [Setup and Configuration](#setup-and-configuration)
    - [Attributes](#attributes)
    - [Categories](#categories)
    - [Distributors](#distributors)
    - [Files](#files)
    - [Brands](#brands)

---

## Examples

---

## Setup and Configuration

### Configuring the SDK

```php
$apieraConfig = new \Apiera\Sdk\Configuration(
    baseUrl: 'https://api.example.com', // The base URL of the API
    userAgent: 'MyAppName/1.0', // Replace with your application user agent
    oauthDomain: 'your-oauth-domain.com', // OAuth2 authentication domain
    oauthClientId: 'your-oauth-client-id', // OAuth2 client ID
    oauthClientSecret: 'your-oauth-client-secret', // OAuth2 client secret
    oauthCookieSecret: 'your-oauth-cookie-secret', // OAuth2 cookie secret
    oauthAudience: 'your-oauth-audience', // OAuth2 audience
    oauthOrganizationId: 'your-organization-id', // OAuth2 organization ID
    cache: $yourCacheInstance, // Pass a CacheItemPoolInterface object
    timeout: 15, // Optional: Request timeout (default: 10 seconds)
    debugMode: true, // Optional: Enable or disable debug mode (default: false)
    options: [] // Optional: Pass your custom Guzzle handlers or middlewares
);
```

### Creating an Instance of the SDK

```php
$sdk = new \Apiera\Sdk\ApieraSdk($apieraConfig);
```

---

## Attributes

### Find Attributes

```php
$requestObject = new \Apiera\Sdk\DTO\Request\Attribute\AttributeRequest(
    store: '/api/v1/stores/520413a8-509a-4048-96e6-81751e315c5d' // Pass the store IRI
);

$responseObject = $sdk->attribute()->find($requestObject);
```

---

### Find Attributes with Filter and Pagination

```php
$requestObject = new \Apiera\Sdk\DTO\Request\Attribute\AttributeRequest(
    store: '/api/v1/stores/520413a8-509a-4048-96e6-81751e315c5d' // Pass the store IRI
);

$queryParamObject = new \Apiera\Sdk\DTO\QueryParameters(
    filters: ['name' => 'some attribute name'] // Add filters as needed
);

$responseObject = $sdk->attribute()->find($requestObject, $queryParamObject);
```

---

### Search a Single Attribute

```php
$requestObject = new \Apiera\Sdk\DTO\Request\Attribute\AttributeRequest(
    store: '/api/v1/stores/520413a8-509a-4048-96e6-81751e315c5d' // Pass the store IRI
);

$queryParamObject = new \Apiera\Sdk\DTO\QueryParameters(
    filters: ['name' => 'Some attribute name'] // Define search criteria
);

try {
    $responseObject = $sdk->attribute()->findOneBy($requestObject, $queryParamObject);
} catch (\Apiera\Sdk\Exception\InvalidRequestException) {
    // Handle the case when the attribute is not found
}
```

---

### Find an Attribute

```php
$requestObject = new \Apiera\Sdk\DTO\Request\Attribute\AttributeRequest(
    iri: '/api/v1/stores/520413a8-509a-4048-96e6-81751e315c5d/attributes/520413a8-509a-4048-96e6-81751e315c5d2' // Use the attribute IRI
);

$responseObject = $sdk->attribute()->get($requestObject);
```

---

### Create an Attribute

```php
$requestObject = new \Apiera\Sdk\DTO\Request\Attribute\AttributeRequest(
    name: 'Some attribute name',
    store: '/api/v1/stores/520413a8-509a-4048-96e6-81751e315c5d' // Pass the store IRI
);

$responseObject = $sdk->attribute()->create($requestObject);
```

---

### Update an Attribute

```php
$requestObject = new \Apiera\Sdk\DTO\Request\Attribute\AttributeRequest(
    name: 'Some new attribute name',
    iri: '/api/v1/stores/520413a8-509a-4048-96e6-81751e315c5d/attributes/520413a8-509a-4048-96e6-81751e315c5d2' // Use the attribute IRI
);

$responseObject = $sdk->attribute()->update($requestObject);
```

## Categories

### Find Categories

```php
$requestObject = new \Apiera\Sdk\DTO\Request\Category\CategoryRequest(
    store: '/api/v1/stores/520413a8-509a-4048-96e6-81751e315c5d' // Pass the store IRI
);

$responseObject = $sdk->category()->find($requestObject);
```

---

### Find Categories with Filter and Pagination

```php
$requestObject = new \Apiera\Sdk\DTO\Request\Category\CategoryRequest(
    store: '/api/v1/stores/520413a8-509a-4048-96e6-81751e315c5d' // Pass the store IRI
);

$queryParamObject = new \Apiera\Sdk\DTO\QueryParameters(
    filters: ['name' => 'some category name'] // Add filters as needed
);

$responseObject = $sdk->category()->find($requestObject, $queryParamObject);
```

---

### Search a Single Category

```php
$requestObject = new \Apiera\Sdk\DTO\Request\Category\CategoryRequest(
    store: '/api/v1/stores/520413a8-509a-4048-96e6-81751e315c5d' // Pass the store IRI
);

$queryParamObject = new \Apiera\Sdk\DTO\QueryParameters(
    filters: ['name' => 'Some category name'] // Define search criteria
);

try {
    $responseObject = $sdk->category()->findOneBy($requestObject, $queryParamObject);
} catch (\Apiera\Sdk\Exception\InvalidRequestException) {
    // Handle the case when the attribute is not found
}
```

---

### Find a Category

```php
$requestObject = new \Apiera\Sdk\DTO\Request\Category\CategoryRequest(
    iri: '/api/v1/stores/520413a8-509a-4048-96e6-81751e315c5d/categories/520413a8-509a-4048-96e6-81751e315c5d2' // Use the category IRI
);

$responseObject = $sdk->category()->get($requestObject);
```

---

### Create a Category

```php
$requestObject = new \Apiera\Sdk\DTO\Request\Category\CategoryRequest(
    name: 'Some category name',
    store: '/api/v1/stores/520413a8-509a-4048-96e6-81751e315c5d', // Pass the store IRI
    description: 'Some category description',
    image: '/api/v1/files/520413a8-509a-4048-96e6-81751e315c5d'
);

$responseObject = $sdk->category()->create($requestObject);
```

---

### Update a Category

```php
$requestObject = new \Apiera\Sdk\DTO\Request\Category\CategoryRequest(
    name: 'Some new category name',
    parent: '/api/v1/stores/520413a8-509a-4048-96e6-81751e315c5d/categories/520413a8-509a-4048-96e6-81751e315c5d5', // Use the parent category iri
    iri: '/api/v1/stores/520413a8-509a-4048-96e6-81751e315c5d/categories/520413a8-509a-4048-96e6-81751e315c5d2' // Use the category IRI
);

$responseObject = $sdk->category()->update($requestObject);
```

# Distributors

### Find Distributors

```php
$requestObject = new \Apiera\Sdk\DTO\Request\Distributor\DistributorRequest(
    store: '/api/v1/stores/520413a8-509a-4048-96e6-81751e315c5d' // Pass the store IRI
);

$responseObject = $sdk->distributor()->find($requestObject);
```

---

### Find Distributors with Filter and Pagination

```php
$requestObject = new \Apiera\Sdk\DTO\Request\Distributor\DistributorRequest(
    store: '/api/v1/stores/520413a8-509a-4048-96e6-81751e315c5d' // Pass the store IRI
);

$queryParamObject = new \Apiera\Sdk\DTO\QueryParameters(
    filters: ['name' => 'some distributor name'] // Add filters as needed
);

$responseObject = $sdk->distributor()->find($requestObject, $queryParamObject);
```

---

### Search a Single Distributor

```php
$requestObject = new \Apiera\Sdk\DTO\Request\Distributor\DistributorRequest(
    store: '/api/v1/stores/520413a8-509a-4048-96e6-81751e315c5d' // Pass the store IRI
);

$queryParamObject = new \Apiera\Sdk\DTO\QueryParameters(
    filters: ['name' => 'Some distributor name'] // Define search criteria
);

try {
    $responseObject = $sdk->distributor()->findOneBy($requestObject, $queryParamObject);
} catch (\Apiera\Sdk\Exception\InvalidRequestException) {
    // Handle the case when the distributor is not found
}
```

---

### Find a Distributor

```php
$requestObject = new \Apiera\Sdk\DTO\Request\Distributor\DistributorRequest(
    iri: '/api/v1/stores/520413a8-509a-4048-96e6-81751e315c5d/distributors/520413a8-509a-4048-96e6-81751e315c5d2' // Use the distributor IRI
);

$responseObject = $sdk->distributor()->get($requestObject);
```

---

### Create a Distributor

```php
$requestObject = new \Apiera\Sdk\DTO\Request\Distributor\DistributorRequest(
    name: 'Some distributor name',
    store: '/api/v1/stores/520413a8-509a-4048-96e6-81751e315c5d' // Pass the store IRI
);

$responseObject = $sdk->distributor()->create($requestObject);
```

---

### Update a Distributor

```php
$requestObject = new \Apiera\Sdk\DTO\Request\Distributor\DistributorRequest(
    name: 'Some new distributor name',
    iri: '/api/v1/stores/520413a8-509a-4048-96e6-81751e315c5d/distributors/520413a8-509a-4048-96e6-81751e315c5d2' // Use the distributor IRI
);

$responseObject = $sdk->distributor()->update($requestObject);
```

## Files

### Find Files

```php
$requestObject = new \Apiera\Sdk\DTO\Request\File\FileRequest(
    url: 'https://example.com/images/product.jpg'
);

$responseObject = $sdk->file()->find($requestObject);
```

---

### Find Files with Filter and Pagination

```php
$requestObject = new \Apiera\Sdk\DTO\Request\File\FileRequest(
    url: 'https://example.com/images/product.jpg'
);

$queryParamObject = new \Apiera\Sdk\DTO\QueryParameters(
    filters: ['name' => 'product.jpg'] // Add filters as needed
);

$responseObject = $sdk->file()->find($requestObject, $queryParamObject);
```

---

### Search a Single File

```php
$requestObject = new \Apiera\Sdk\DTO\Request\File\FileRequest(
    url: 'https://example.com/images/product.jpg'
);

$queryParamObject = new \Apiera\Sdk\DTO\QueryParameters(
    filters: ['name' => 'product.jpg'] // Define search criteria
);

try {
    $responseObject = $sdk->file()->findOneBy($requestObject, $queryParamObject);
} catch (\Apiera\Sdk\Exception\InvalidRequestException) {
    // Handle the case when the file is not found
}
```

---

### Find a File

```php
$requestObject = new \Apiera\Sdk\DTO\Request\File\FileRequest(
    url: 'https://example.com/images/product.jpg',
    iri: '/api/v1/files/520413a8-509a-4048-96e6-81751e315c5d' // Use the file IRI
);

$responseObject = $sdk->file()->get($requestObject);
```

---

### Create a File

```php
$requestObject = new \Apiera\Sdk\DTO\Request\File\FileRequest(
    url: 'https://example.com/images/product.jpg',
    name: 'product.jpg'
);

$responseObject = $sdk->file()->create($requestObject);
```

---

### Delete a File

```php
$requestObject = new \Apiera\Sdk\DTO\Request\File\FileRequest(
    url: 'https://example.com/images/product.jpg',
    iri: '/api/v1/files/520413a8-509a-4048-96e6-81751e315c5d' // Use the file IRI
);

$sdk->file()->delete($requestObject);
```

## Brands

### Find Brands

```php
$requestObject = new \Apiera\Sdk\DTO\Request\Brand\BrandRequest(
    store: '/api/v1/stores/520413a8-509a-4048-96e6-81751e315c5d' // Pass the store IRI
);

$responseObject = $sdk->brand()->find($requestObject);
```

---

### Find Brands with Filter and Pagination

```php
$requestObject = new \Apiera\Sdk\DTO\Request\Brand\BrandRequest(
    store: '/api/v1/stores/520413a8-509a-4048-96e6-81751e315c5d' // Pass the store IRI
);

$queryParamObject = new \Apiera\Sdk\DTO\QueryParameters(
    filters: ['name' => 'some brand name'] // Add filters as needed
);

$responseObject = $sdk->brand()->find($requestObject, $queryParamObject);
```

---

### Search a Single Brand

```php
$requestObject = new \Apiera\Sdk\DTO\Request\Brand\BrandRequest(
    store: '/api/v1/stores/520413a8-509a-4048-96e6-81751e315c5d' // Pass the store IRI
);

$queryParamObject = new \Apiera\Sdk\DTO\QueryParameters(
    filters: ['name' => 'Some brand name'] // Define search criteria
);

try {
    $responseObject = $sdk->brand()->findOneBy($requestObject, $queryParamObject);
} catch (\Apiera\Sdk\Exception\InvalidRequestException) {
    // Handle the case when the brand is not found
}
```

---

### Find a Brand

```php
$requestObject = new \Apiera\Sdk\DTO\Request\Brand\BrandRequest(
    iri: '/api/v1/stores/520413a8-509a-4048-96e6-81751e315c5d/brands/520413a8-509a-4048-96e6-81751e315c5d2' // Use the brand IRI
);

$responseObject = $sdk->brand()->get($requestObject);
```

---

### Create a Brand

```php
$requestObject = new \Apiera\Sdk\DTO\Request\Brand\BrandRequest(
    name: 'Some brand name',
    store: '/api/v1/stores/520413a8-509a-4048-96e6-81751e315c5d', // Pass the store IRI
    description: 'Some brand description',
    image: '/api/v1/files/520413a8-509a-4048-96e6-81751e315c5d'
);

$responseObject = $sdk->brand()->create($requestObject);
```

---

### Update a Brand

```php
$requestObject = new \Apiera\Sdk\DTO\Request\Brand\BrandRequest(
    name: 'Some new brand name',
    description: 'Updated brand description',
    image: '/api/v1/files/520413a8-509a-4048-96e6-81751e315c5d',
    iri: '/api/v1/stores/520413a8-509a-4048-96e6-81751e315c5d/brands/520413a8-509a-4048-96e6-81751e315c5d2' // Use the brand IRI
);

$responseObject = $sdk->brand()->update($requestObject);
```