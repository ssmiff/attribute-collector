<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Ssmith\AttributeCollector\ClassAttributeReader;
use Ssmith\AttributeCollector\Collection\AttributeCollection;
use Ssmith\AttributeCollector\Tests\fixtures\Attributes\TargetClass;
use Ssmith\AttributeCollector\Tests\fixtures\Attributes\TargetMethod;
use Ssmith\AttributeCollector\Tests\fixtures\Attributes\TargetProperty;
use Ssmith\AttributeCollector\Tests\fixtures\ReflectionTestClass;

#[CoversClass(ClassAttributeReader::class)]
class ClassAttributeReaderTest extends TestCase
{
    #[Test]
    public function test_reads_attributes(): void
    {
        $instance = new ReflectionTestClass('name', 11);
        $reader = new ClassAttributeReader($instance);

        $collection = new AttributeCollection();
        $reader->read($collection);
        $attributes = $collection->all();

        $this->assertCount(3, $collection);

        // Class Attribute
        $this->assertArrayHasKey(TargetClass::class, $attributes);
        $this->assertCount(1, $attributes[TargetClass::class]);

        $classAttr = $attributes[TargetClass::class][0];
        $this->assertInstanceOf(TargetClass::class, $classAttr->instance);
        $this->assertSame('class', $classAttr->kind);

        // Property Attributes
        $this->assertArrayHasKey(TargetProperty::class, $attributes);
        $this->assertCount(2, $attributes[TargetProperty::class]);

        $propAttr1 = $attributes[TargetProperty::class][0];
        $this->assertInstanceOf(TargetProperty::class, $propAttr1->instance);
        $this->assertSame('property', $propAttr1->kind);
        $this->assertSame(
            [
                'class' => ReflectionTestClass::class,
                'property' => 'age',
            ],
            $propAttr1->meta,
        );

        $propAttr2 = $attributes[TargetProperty::class][1];
        $this->assertInstanceOf(TargetProperty::class, $propAttr2->instance);
        $this->assertSame('property', $propAttr2->kind);

        // Method Attributes
        $this->assertArrayHasKey(TargetMethod::class, $attributes);
        $this->assertCount(1, $attributes[TargetMethod::class]);

        $methodAttr = $attributes[TargetMethod::class][0];

        $this->assertInstanceOf(TargetMethod::class, $methodAttr->instance);
        $this->assertSame('method', $methodAttr->kind);
    }

    #[Test]
    public function test_skips_builtin_attribute_class(): void
    {
        $instance = new TargetClass();
        $reader = new ClassAttributeReader($instance);

        $collection = new AttributeCollection();
        $reader->read($collection);

        $this->assertCount(0, $collection);
    }
}
