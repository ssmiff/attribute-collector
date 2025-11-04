<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Ssmith\AttributeCollector\AttributeDetails;
use Ssmith\AttributeCollector\AttributeRegistry;
use Ssmith\AttributeCollector\ClassAttributeReader;
use Ssmith\AttributeCollector\Collection\AttributeCollection;
use Ssmith\AttributeCollector\Tests\fixtures\Attributes\TargetClass;
use Ssmith\AttributeCollector\Tests\fixtures\Attributes\TargetMethod;
use Ssmith\AttributeCollector\Tests\fixtures\Attributes\TargetProperty;
use Ssmith\AttributeCollector\Tests\fixtures\ReflectionTestClass;

#[CoversClass(AttributeRegistry::class)]
class AttributeRegistryTest extends TestCase
{
    #[Test]
    public function can_read_by_attribute(): void
    {
        $registry = $this->createRegistry();

        $attributeIterator = $registry->forAttribute(TargetProperty::class);

        $attributes = iterator_to_array($attributeIterator);

        $this->assertCount(2, $attributes);

        $this->assertInstanceOf(TargetProperty::class, $attributes[0]->instance);
        $this->assertSame(AttributeDetails::PROPERTY_KIND, $attributes[0]->kind);
        $this->assertSame(
            [
                'class' => ReflectionTestClass::class,
                'property' => 'age',
            ],
            $attributes[0]->meta,
        );

        $this->assertInstanceOf(TargetProperty::class, $attributes[1]->instance);
        $this->assertSame(AttributeDetails::PROPERTY_KIND, $attributes[1]->kind);
        $this->assertSame(
            [
                'class' => ReflectionTestClass::class,
                'property' => 'name',
            ],
            $attributes[1]->meta,
        );
    }

    #[Test]
    public function can_read_by_class(): void
    {
        $registry = $this->createRegistry();

        $attributeIterator = $registry->forClass(ReflectionTestClass::class);

        $attributes = iterator_to_array($attributeIterator);

        $this->assertCount(1, $attributes);

        $this->assertInstanceOf(TargetClass::class, $attributes[0]->instance);
        $this->assertSame(AttributeDetails::CLASS_KIND, $attributes[0]->kind);
        $this->assertSame(
            [
                'class' => ReflectionTestClass::class,
            ],
            $attributes[0]->meta,
        );
    }

    #[Test]
    public function can_read_by_method(): void
    {
        $registry = $this->createRegistry();

        $attributeIterator = $registry->forMethod(ReflectionTestClass::class, 'testMethod');;

        $attributes = iterator_to_array($attributeIterator);

        $this->assertCount(1, $attributes);

        $this->assertInstanceOf(TargetMethod::class, $attributes[0]->instance);
        $this->assertSame(AttributeDetails::METHOD_KIND, $attributes[0]->kind);
        $this->assertSame(
            [
                'class' => ReflectionTestClass::class,
                'method' => 'testMethod',
            ],
            $attributes[0]->meta,
        );
    }

    #[Test]
    public function can_read_by_properties(): void
    {
        $registry = $this->createRegistry();

        $attributeIterator = $registry->forProperty(ReflectionTestClass::class, 'age');;

        $attributes = iterator_to_array($attributeIterator);

        $this->assertCount(1, $attributes);

        $this->assertInstanceOf(TargetProperty::class, $attributes[0]->instance);
        $this->assertSame(AttributeDetails::PROPERTY_KIND, $attributes[0]->kind);
        $this->assertSame(
            [
                'class' => ReflectionTestClass::class,
                'property' => 'age',
            ],
            $attributes[0]->meta,
        );
    }

    private function createRegistry(): AttributeRegistry
    {
        $instance = new ReflectionTestClass('name', 11);
        $reader = new ClassAttributeReader($instance);

        $collection = new AttributeCollection();
        $reader->read($collection);

        return new AttributeRegistry($collection);
    }
}
