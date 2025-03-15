# Apiera PHP SDK

A PHP SDK for interacting with the Apiera API. Built with PHP 8.3, utilizing PSR standards and Guzzle HTTP client.

## Requirements

- PHP 8.3 or higher
- Composer

Package dependencies will be installed automatically via Composer.

## Installation

Install via Composer:

```bash
composer require apiera/php-sdk
```

## Configuration Options

The SDK can be configured with the following options:

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
    options: [], // Optional: Pass your custom Guzzle handlers or middlewares
    defaultIntegration: 'integration-iri', // Optional: Pass a default integration iri reference
    defaultInventoryLocation: 'inventory-location-iri', // Optional: Pass a default inventory location iri reference
    defaultStore: 'store-iri' // Optional: Pass a default store iri reference
);
```

## Using the SDK

[`ApieraSdk`](src/ApieraSdk.php) is a centralized class providing access to all available API resources:

```php
$apieraSdk = new \Apiera\Sdk\ApieraSdk($apieraConfig);

$categoryRequest = new \Apiera\Sdk\DTO\Request\Category\CategoryRequest(
    name: 'Hardware',
    store: '/api/v1/stores/520413a8-509a-4048-96e6-81751e315c5d',
    description: 'Some category description',
    parent: '/api/v1/stores/520413a8-509a-4048-96e6-81751e315c5d/categories/520413a8-509a-4048-96e6-81751e315c5d2'
);

$categoryResponse = $apieraSdk->category()->create($categoryRequest);

// Do something with $categoryResponse
```

More examples can be found [here](docs/examples.md)

## Support

For support, please contact fredrik.tveraaen@apiera.io or visit our [documentation](https://app.swaggerhub.com/apis-docs/FREDRIKTVERAAEN/apiera-organization-api/1.2.0).

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE.md) file for details