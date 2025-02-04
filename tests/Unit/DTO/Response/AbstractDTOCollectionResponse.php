<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Response;

use Apiera\Sdk\DTO\Response\AbstractCollectionResponse;
use Apiera\Sdk\DTO\Response\PartialCollectionView;
use Apiera\Sdk\Interface\DTO\JsonLDCollectionInterface;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

abstract class AbstractDTOCollectionResponse extends TestCase
{
    public function testInstanceOf(): void
    {
        $collectionClass = $this->getCollectionClass();
        $response = new $collectionClass(...$this->getCollectionData());

        $this->assertInstanceOf($collectionClass, $response);
        $this->assertInstanceOf(AbstractCollectionResponse::class, $response);
        $this->assertInstanceOf(JsonLDCollectionInterface::class, $response);
    }

    /**
     * @throws \ReflectionException
     */
    public function testClassIsReadonly(): void
    {
        $reflection = new ReflectionClass($this->getCollectionClass());
        $this->assertTrue($reflection->isReadonly(), 'Class should be readonly');
    }

    public function testConstructorAndGetters(): void
    {
        $collectionClass = $this->getCollectionClass();
        $data = $this->getCollectionData();
        $response = new $collectionClass(...$data);

        $this->assertEquals($data['ldContext'], $response->getLdContext());
        $this->assertEquals($data['ldId'], $response->getLdId());
        $this->assertEquals($data['ldType'], $response->getLdType());
        $this->assertContainsOnlyInstancesOf($this->getMemberClass(), $response->getLdMembers());
        $this->assertEquals($data['ldTotalItems'], $response->getLdTotalItems());

        if (isset($data['ldView'])) {
            $view = $response->getLdView();
            $this->assertInstanceOf(PartialCollectionView::class, $view);
            $this->assertEquals($data['ldView']->getLdId(), $view->getLdId());
            $this->assertEquals($data['ldView']->getLdFirst(), $view->getLdFirst());
            $this->assertEquals($data['ldView']->getLdLast(), $view->getLdLast());
            $this->assertEquals($data['ldView']->getLdNext(), $view->getLdNext());
            $this->assertEquals($data['ldView']->getLdPrevious(), $view->getLdPrevious());
        } else {
            $this->assertNull($response->getLdView());
        }
    }

    public function testEmptyCollection(): void
    {
        $collectionClass = $this->getCollectionClass();
        $data = $this->getCollectionData();
        $data['ldMembers'] = [];
        $data['ldTotalItems'] = 0;

        $response = new $collectionClass(...$data);

        $this->assertEmpty($response->getLdMembers());
        $this->assertEquals(0, $response->getLdTotalItems());
    }

    public function testNullView(): void
    {
        $collectionClass = $this->getCollectionClass();
        $data = $this->getCollectionData();
        $data['ldView'] = null;

        $response = new $collectionClass(...$data);
        $this->assertNull($response->getLdView());
    }

    public function testNullPreviousInView(): void
    {
        $data = $this->getCollectionData();

        if (!isset($data['ldView'])) {
            $this->addToAssertionCount(1);

            return;
        }

        $view = $data['ldView'];
        $this->assertNull($view->getLdPrevious(), 'First page should have null previous page');
    }

    /**
     * The collection response class being tested
     *
     * @return class-string The fully qualified class name
     */
    abstract protected function getCollectionClass(): string;

    /**
     * The response class being tested
     *
     * @return class-string The fully qualified class name
     */
    abstract protected function getMemberClass(): string;

    /**
     * Sample data for constructing a response with populated values
     *
     * @return array<string, mixed>
     */
    abstract protected function getCollectionData(): array;
}
