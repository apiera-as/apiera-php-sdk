# Apiera PHP SDK

A PHP SDK for interacting with the Apiera API. Built with PHP 8.3, utilizing PSR standards and Guzzle HTTP client.

## Requirements

- PHP 8.3 or higher
- Composer
- Guzzle HTTP client
- PSR-7 implementation

## Installation

Install via Composer:

```bash
composer require apiera/sdk
```

## Basic Usage

```php
use Apiera\Sdk\Configuration;
use Apiera\Sdk\Client;

// Create configuration
$config = new Configuration(
    baseUrl: 'https://api.apiera.io',
    timeout: 30,
    userAgent: 'MyApp/1.0',
    debugMode: false
);

// Initialize client
$client = new Client($config);
```

## Working with Categories

```php
use Apiera\Sdk\Resource\CategoryResource;
use Apiera\Sdk\DTO\Request\Category\CategoryRequest;
use Apiera\Sdk\DataMapper\CategoryDataMapper;

// Initialize resource
$categoryResource = new CategoryResource(
    client: $client,
    mapper: new CategoryDataMapper()
);

// Create a category
$request = new CategoryRequest(
    name: 'Electronics',
    store: '/stores/1',
    description: 'Electronic products category'
);

$category = $categoryResource->create($request);

// Find categories
$categories = $categoryResource->find($request);

// Get single category
$category = $categoryResource->get($request);

// Update category
$updatedCategory = $categoryResource->update($request);

// Delete category
$categoryResource->delete($request);
```

## Configuration Options

The SDK can be configured with the following options:

```php
$config = new Configuration(
    timeout: 10,                    // Request timeout in seconds
    debugMode: false,               // Enable debug mode
    baseUrl: 'https://api.example.com',
    userAgent: 'MyApp/1.0',
    authDomain: 'auth.example.com',
    authClientId: 'client_id',
    authClientSecret: 'client_secret',
    authCookieSecret: 'cookie_secret',
    authOrganizationId: 'org_id'
);
```

## Query Parameters

Use QueryParameters DTO for filtering and pagination:

```php
use Apiera\Sdk\DTO\QueryParameters;

$params = new QueryParameters(
    params: ['sort' => 'name'],
    filters: ['category' => 'electronics'],
    page: 1
);

$results = $resource->find($request, $params);
```

## Error Handling

The SDK uses custom exceptions for error handling:

```php
use Apiera\Sdk\Exception\ClientException;
use Apiera\Sdk\Exception\InvalidRequestException;

try {
    $result = $resource->get($request);
} catch (ClientException $e) {
    // Handle API client errors
    echo $e->getMessage();
    echo $e->getRequest();  // Get original request
    echo $e->getResponse(); // Get API response if available
} catch (InvalidRequestException $e) {
    // Handle invalid request errors
    echo $e->getMessage();
}
```

## Data Transfer Objects (DTOs)

### Request DTOs

Request DTOs encapsulate data for API requests:

```php
use Apiera\Sdk\DTO\Request\Category\CategoryRequest;

$request = new CategoryRequest(
    name: 'Electronics',
    store: '/stores/1',
    description: 'Electronics category',
    parent: '/categories/parent',
    image: '/files/image.jpg'
);
```

### Response DTOs

Response DTOs provide strongly-typed access to API responses:

```php
// Single resource response
$category = $resource->get($request);
echo $category->getName();
echo $category->getDescription();

// Collection response
$categories = $resource->find($request);
echo $categories->getTotalItems();
foreach ($categories->getMembers() as $category) {
    echo $category->getName();
}
```

## Content Types

The SDK supports the following content types:

- `application/ld+json` - Default content type for requests
- `application/merge-patch+json` - Used for PATCH requests

## Authentication

OAuth2 authentication support is available through the OAuth2Handler:

```php
use Apiera\Sdk\Oauth2Handler;

$auth = new Oauth2Handler();
$token = $auth->getAccessToken();
$expiration = $auth->getTokenExpiration();
```

## Development

### CI/CD
Our project uses GitHub Actions for continuous integration and deployment. We have automated workflows for:
- Code quality checks (PHPStan, PHPCS)
- Test execution
- Release management
- Code coverage reporting

For detailed information about our CI/CD setup, including workflows, tools configuration, and release procedures, see [CI/CD Documentation](docs/CI_CD.md).

### Adding New Resources

1. Create Request/Response DTOs in appropriate namespace
2. Implement DataMapper for the resource
3. Create Resource class implementing RequestResourceInterface
4. Add resource type to LdType enum

### Testing

Run tests:

```bash
composer test
```

### Coding Standards

This project follows PSR-12 coding standards. Run PHP CS Fixer:

```bash
composer cs-fix
```

## Support

For support, please contact support@apiera.io or visit our documentation at https://docs.apiera.io.

## License

[License information here]