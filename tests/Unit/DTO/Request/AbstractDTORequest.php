<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Request;

use Apiera\Sdk\Interface\DTO\RequestInterface;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

abstract class AbstractDTORequest extends TestCase
{
    /**
     * @throws \ReflectionException
     */
    public function testImplementsInterface(): void
    {
        $requestClass = $this->getRequestClass();
        $reflection = new ReflectionClass($requestClass);

        $this->assertTrue(
            $reflection->implementsInterface(RequestInterface::class),
            sprintf('Class %s should implement RequestInterface', $requestClass)
        );
    }

    /**
     * @throws \ReflectionException
     */
    public function testClassIsReadonly(): void
    {
        $reflection = new ReflectionClass($this->getRequestClass());
        $this->assertTrue($reflection->isReadonly(), 'Class should be readonly');
    }

    /**
     * @throws \ReflectionException
     */
    public function testPropertiesAreReadonly(): void
    {
        $reflection = new ReflectionClass($this->getRequestClass());
        $properties = $reflection->getProperties();

        foreach ($properties as $property) {
            $this->assertTrue(
                $property->isReadonly(),
                sprintf('Property %s should be readonly', $property->getName())
            );
        }
    }

    public function testGetters(): void
    {
        $requestClass = $this->getRequestClass();
        $request = new $requestClass(...$this->getConstructorParams());

        foreach ($this->getConstructorParams() as $property => $value) {
            $getter = 'get' . ucfirst($property);
            $this->assertEquals($value, $request->$getter());
        }
    }

    /**
     * @return class-string The request DTO class being tested
     */
    abstract protected function getRequestClass(): string;

    /**
     * @return array<string, mixed> Full parameters for constructor testing
     */
    abstract protected function getConstructorParams(): array;
}
