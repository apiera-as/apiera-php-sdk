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
    timeout: 10,
    debugMode: true,
    baseUrl: 'https://api.example.com',
    userAgent: 'MyApp/1.0',
    authDomain: 'auth.example.com',
    authClientId: 'client_id',
    authClientSecret: 'client_secret',
    authCookieSecret: 'cookie_secret',
    authAudience: 'https://audience.example.com',
    authOrganizationId: 'org_id',
    cache: new CacheItemPoolInterface()
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

For support, please contact fredrik.tveraaen@apiera.io or visit our [documentation](https://app.swaggerhub.com/apis/FREDRIKTVERAAEN/apiera-organization-api/1.2.0#/).

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE.md) file for details