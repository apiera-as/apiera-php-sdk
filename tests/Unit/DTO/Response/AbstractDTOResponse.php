<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Response;

use Apiera\Sdk\DTO\Response\AbstractResponse;
use Apiera\Sdk\Enum\LdType;
use Apiera\Sdk\Interface\DTO\JsonLDInterface;
use Apiera\Sdk\Interface\DTO\ResponseInterface;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Symfony\Component\Uid\Uuid;

abstract class AbstractDTOResponse extends TestCase
{
    public function testInstanceOf(): void
    {
        $responseClass = $this->getResponseClass();
        $response = new $responseClass(...$this->getResponseData());

        $this->assertInstanceOf($responseClass, $response);
        $this->assertInstanceOf(AbstractResponse::class, $response);
        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertInstanceOf(JsonLDInterface::class, $response);
    }

    public function testClassIsReadonly(): void
    {
        $reflection = new ReflectionClass($this->getResponseClass());
        $this->assertTrue($reflection->isReadonly(), 'Class should be readonly');
    }

    public function testPropertiesAreReadonly(): void
    {
        $reflection = new ReflectionClass($this->getResponseClass());
        $properties = $reflection->getProperties();

        foreach ($properties as $property) {
            $this->assertTrue(
                $property->isReadonly(),
                sprintf('Property %s should be readonly', $property->getName())
            );
        }
    }

    public function testConstructorWithPopulatedValues(): void
    {
        $responseClass = $this->getResponseClass();
        $data = $this->getResponseData();
        $response = new $responseClass(...$data);

        // Test common JSON-LD fields
        $this->assertEquals($data['ldId'], $response->getLdId());
        $this->assertEquals($this->getExpectedLdType(), $response->getLdType());

        // Test UUID field
        $this->assertTrue(Uuid::isValid($response->getUuid()->toRfc4122()));
        $this->assertEquals($data['uuid']->toRfc4122(), $response->getUuid()->toRfc4122());

        // Test DateTime fields
        $this->assertInstanceOf(DateTimeImmutable::class, $response->getCreatedAt());
        $this->assertInstanceOf(DateTimeImmutable::class, $response->getUpdatedAt());
        $this->assertEquals($data['createdAt']->format(DATE_ATOM), $response->getCreatedAt()->format(DATE_ATOM));
        $this->assertEquals($data['updatedAt']->format(DATE_ATOM), $response->getUpdatedAt()->format(DATE_ATOM));

        // Test remaining fields via reflection
        $reflection = new ReflectionClass($responseClass);

        foreach ($data as $property => $value) {
            if (in_array($property, ['ldId', 'ldType', 'uuid', 'createdAt', 'updatedAt'])) {
                // Skip already tested fields
                continue;
            }

            if (!$reflection->hasProperty($property)) {
                continue;
            }

            $getter = 'get' . ucfirst($property);
            $this->assertEquals($value, $response->$getter());
        }
    }

    public function testNullableFieldHandling(): void
    {
        $nullableFields = $this->getNullableFields();

        if (count($nullableFields) === 0) {
            // Avoid risky test
            $this->addToAssertionCount(1);

            return;
        }

        $responseClass = $this->getResponseClass();
        $data = $this->getResponseData();

        // Set all nullable fields to null
        foreach ($nullableFields as $field => $defaultValue) {
            $data[$field] = null;
        }

        $response = new $responseClass(...$data);

        // Verify nullable fields handle null values correctly
        foreach ($nullableFields as $field => $defaultValue) {
            $getter = 'get' . ucfirst($field);
            $this->assertNull($response->$getter(), "Nullable field $field should accept null value");
        }
    }

    /**
     * The response class being tested
     *
     * @return class-string The fully qualified class name
     */
    abstract protected function getResponseClass(): string;

    /**
     * Sample data for constructing a response with populated values
     *
     * @return array<string, mixed>
     */
    abstract protected function getResponseData(): array;

    /**
     * The expected LdType for this response
     */
    abstract protected function getExpectedLdType(): LdType;

    /**
     * A list of nullable fields and their default values
     *
     * @return array<string, mixed>
     */
    abstract protected function getNullableFields(): array;
}
