# DTO Implementation Guide

## Overview

The Data Transfer Object (DTO) layer handles the transformation of API data to and from strongly-typed PHP objects. It consists of three main components:

- Request DTOs: For sending data to the API
- Response DTOs: For receiving data from the API
- Collection Response DTOs: For handling paginated lists of resources

## Organization

```
src/
└── DTO/
    ├── Request/
    │   └── Category/
    │       └── CategoryRequest.php
    ├── Response/
    │   ├── AbstractResourceResponse.php
    │   ├── AbstractCollectionResponse.php
    │   └── Category/
    │       ├── CategoryResponse.php
    │       └── CategoryCollectionResponse.php
    └── QueryParameters.php
```

## Implementation Guidelines

### 1. Request DTOs

Request DTOs must:
- Implement `RequestInterface`
- Be marked as `final readonly`
- Use constructor property promotion
- Implement `toArray()` for serialization
- Include nullable types where appropriate

Example:
```php
final readonly class CategoryRequest implements RequestInterface
{
    public function __construct(
        private string $name,
        private ?string $store = null,
        private ?string $description = null,
        private ?string $parent = null,
        private ?string $image = null,
        private ?string $iri = null
    ) {
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'parent' => $this->parent,
            'image' => $this->image,
        ];
    }
}
```

### 2. Response DTOs

Response DTOs must:
- Extend `AbstractResourceResponse`
- Implement `ResponseInterface`
- Be marked as `final readonly`
- Include all JSON-LD fields from parent class
- Use constructor property promotion
- Document all properties with PHPDoc

Example:
```php
final readonly class CategoryResponse extends AbstractResourceResponse 
{
    public function __construct(
        string $context,
        string $id,
        LdType $type,
        Uuid $uuid,
        DateTimeInterface $createdAt,
        DateTimeInterface $updatedAt,
        private string $name,
        private string $store,
        private ?string $description = null,
        private ?string $parent = null,
        private ?string $image = null
    ) {
        parent::__construct(
            $context,
            $id,
            $type,
            $uuid,
            $createdAt,
            $updatedAt
        );
    }
}
```

### 3. Collection Response DTOs

Collection DTOs must:
- Extend `AbstractCollectionResponse`
- Use generics to type-hint member arrays
- Override `getMembers()` with specific return type
- Be marked as `final readonly`

Example:
```php
/**
 * @template-extends AbstractCollectionResponse<CategoryResponse>
 */
final readonly class CategoryCollectionResponse extends AbstractCollectionResponse
{
    /**
     * @return array<CategoryResponse>
     */
    public function getMembers(): array
    {
        return parent::getMembers();
    }
}
```

### 4. Query Parameters

The `QueryParameters` DTO provides:
- Filtering support
- Pagination parameters
- Generic query parameters
- Array serialization

Example:
```php
$params = new QueryParameters(
    params: ['sort' => 'name'],
    filters: ['category' => 'electronics'],
    page: 1
);
```

## Data Mapping

Each resource requires a DataMapper that implements `DataMapperInterface`:

```php
class CategoryDataMapper implements DataMapperInterface
{
    public function fromResponse(array $responseData): ResponseInterface;
    public function fromCollectionResponse(array $collectionData): JsonLDInterface;
    public function toRequestData(RequestInterface $requestDto): array;
}
```

## Type Safety

### Generics

Use PHP 8.3's improved generic type hints:

```php
/** @template T of ResponseInterface */
abstract class AbstractCollectionResponse
{
    /** @param array<T> $members */
    public function __construct(
        private readonly array $members,
    ) {}

    /** @return array<T> */
    public function getMembers(): array
    {
        return $this->members;
    }
}
```

### Readonly Properties

Always use readonly properties for immutability:

```php
readonly class CategoryRequest
{
    public function __construct(
        private string $name,
        private ?string $description = null
    ) {}
}
```

## Testing Guidelines

### Unit Tests

Test cases should cover:
- Object construction
- Getter methods
- Null handling
- Type enforcement
- Serialization/deserialization

Example:
```php
public function testCategoryRequestSerialization(): void
{
    $request = new CategoryRequest(
        name: 'Test Category',
        description: 'Test Description'
    );

    $data = $request->toArray();
    $this->assertArrayHasKey('name', $data);
    $this->assertEquals('Test Category', $data['name']);
}
```

### Integration Tests

Test the full request/response cycle:
- Request creation
- Data mapping
- Response handling
- Collection handling

## Best Practices

1. **Immutability**
    - Use readonly properties
    - Avoid setters
    - Return new instances for modifications

2. **Type Safety**
    - Use strict_types declaration
    - Leverage PHP 8.3 type system
    - Document array types with generics

3. **Validation**
    - Validate in constructors
    - Use value objects
    - Handle nulls explicitly

4. **Documentation**
    - Full PHPDoc blocks
    - Clear property descriptions
    - Generic type documentation

5. **Performance**
    - Lazy loading when appropriate
    - Minimal object graph
    - Efficient serialization

## Common Patterns

### Value Objects
```php
final readonly class Price
{
    public function __construct(
        private float $amount,
        private string $currency
    ) {}
}
```

### Nullable Properties
```php
public function __construct(
    private string $name,
    private ?string $description = null
) {}
```

### Collection Typing
```php
/** @return array<CategoryResponse> */
public function getMembers(): array
```

## Upgrade Guide

When upgrading DTOs:

1. Maintain backward compatibility
2. Document breaking changes
3. Update tests
4. Version appropriately
5. Update mappers

## Related Components

- `LdType`: Enum for JSON-LD types
- `DataMapperInterface`: Data transformation contract
- `ClientInterface`: HTTP client abstraction
- `QueryParameters`: Request parameters DTO