<?php

declare(strict_types=1);

namespace Collection;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Ssmith\AttributeCollector\AttributeDetails;
use Ssmith\AttributeCollector\Collection\AttributeCollection;
use Ssmith\AttributeCollector\Tests\fixtures\Attributes\TargetClass;
use Ssmith\AttributeCollector\Tests\fixtures\Attributes\TargetMethod;
use Ssmith\AttributeCollector\Tests\fixtures\Attributes\TargetProperty;
use Ssmith\AttributeCollector\Tests\fixtures\ReflectionTestClass;

#[CoversClass(AttributeCollection::class)]
class AttributeCollectionTest extends TestCase
{
    #[Test]
    public function can_add_classes(): void
    {
        $collection = new AttributeCollection();

        $reflectionClass = new ReflectionClass(ReflectionTestClass::class);
        $attribute = $reflectionClass->getAttributes()[0];

        $collection->addClassAttributes(
            'className',
            $attribute,
        );

        $items = $collection->all();

        $this->assertCount(1, $collection);
        $this->assertCount(1, $items);

        $attributes = $items[TargetClass::class];

        $this->assertCount(1, $attributes);
        $this->assertInstanceOf(AttributeDetails::class, $attributes[0]);
        $this->assertSame(AttributeDetails::CLASS_KIND, $attributes[0]->kind);
        $this->assertSame('className', $attributes[0]->meta['class']);
        $this->assertInstanceOf(TargetClass::class, $attributes[0]->instance);

        $collection->clear();

        $this->assertCount(0, $collection);
    }

    #[Test]
    public function can_add_methods(): void
    {
        $collection = new AttributeCollection();

        $reflectionClass = new ReflectionClass(ReflectionTestClass::class);
        $reflectionMethod = $reflectionClass->getMethod('testMethod');
        $attribute = $reflectionMethod->getAttributes()[0];

        $collection->addMethodAttributes(
            'className',
            'testMethod',
            $attribute,
        );

        $items = $collection->all();

        $this->assertCount(1, $collection);
        $this->assertCount(1, $items);

        $attributes = $items[TargetMethod::class];

        $this->assertCount(1, $attributes);
        $this->assertInstanceOf(AttributeDetails::class, $attributes[0]);
        $this->assertSame(AttributeDetails::METHOD_KIND, $attributes[0]->kind);
        $this->assertSame('className', $attributes[0]->meta['class']);
        $this->assertSame('testMethod', $attributes[0]->meta['method']);
        $this->assertInstanceOf(TargetMethod::class, $attributes[0]->instance);

        $collection->clear();

        $this->assertCount(0, $collection);
    }

    #[Test]
    public function can_add_properties(): void
    {
        $collection = new AttributeCollection();

        $reflectionClass = new ReflectionClass(ReflectionTestClass::class);
        $reflectionProperties = $reflectionClass->getProperties();
        $attribute = $reflectionProperties[0]->getAttributes()[0];

        $collection->addPropertyAttributes(
            'className',
            'testProperty',
            $attribute,
        );

        $items = $collection->all();

        $this->assertCount(1, $collection);
        $this->assertCount(1, $items);

        $attributes = $items[TargetProperty::class];

        $this->assertCount(1, $attributes);
        $this->assertInstanceOf(AttributeDetails::class, $attributes[0]);
        $this->assertSame(AttributeDetails::PROPERTY_KIND, $attributes[0]->kind);
        $this->assertSame('className', $attributes[0]->meta['class']);
        $this->assertSame('testProperty', $attributes[0]->meta['property']);
        $this->assertInstanceOf(TargetProperty::class, $attributes[0]->instance);

        $collection->clear();

        $this->assertCount(0, $collection);
    }
}
