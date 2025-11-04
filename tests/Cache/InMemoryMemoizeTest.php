<?php

declare(strict_types=1);

namespace Cache;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Ssmith\AttributeCollector\Cache\InMemoryMemoizer;
use Ssmith\AttributeCollector\Collection\AttributeCollection;
use Ssmith\AttributeCollector\Tests\fixtures\ReflectionTestClass;

#[CoversClass(InMemoryMemoizer::class)]
class InMemoryMemoizeTest extends TestCase
{
    #[Test]
    public function test_returns_nothing_if_no_cache(): void
    {
        $memoize = new InMemoryMemoizer();

        $this->assertNull($memoize->load());
    }

    #[Test]
    public function test_can_memoize(): void
    {
        $collection = new AttributeCollection();

        $reflectionClass = new ReflectionClass(ReflectionTestClass::class);
        $attribute = $reflectionClass->getAttributes()[0];
        $collection->addClassAttributes('className', $attribute);

        $memoize = new InMemoryMemoizer();

        $memoize->save($collection);

        $this->assertSame($collection->all(), $memoize->load()->all());
    }
}
