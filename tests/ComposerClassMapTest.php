<?php

declare(strict_types=1);

namespace Ssmith\AttributeCollector\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Ssmith\AttributeCollector\ClassMap;
use Ssmith\AttributeCollector\ComposerClassMap;
use Ssmith\AttributeCollector\Tests\fixtures\Attributes\TargetClass;
use Ssmith\AttributeCollector\Tests\fixtures\Attributes\TargetMethod;
use Ssmith\AttributeCollector\Tests\fixtures\Attributes\TargetProperty;
use Ssmith\AttributeCollector\Tests\fixtures\ReflectionTestClass;
use Ssmith\AttributeCollector\Tests\fixtures\TestClass1;
use Ssmith\AttributeCollector\Tests\fixtures\TestClass2;

#[CoversClass(ComposerClassMap::class)]
class ComposerClassMapTest extends TestCase
{
    #[Test]
    public function instance_of_class_map_is_returned(): void
    {
        $this->assertInstanceOf(ClassMap::class, new ComposerClassMap([__DIR__ . '/fixtures']));
    }

    #[Test]
    public function get_classes_returns_expected_classes(): void
    {
        $classMap = new ComposerClassMap([__DIR__ . '/fixtures', __DIR__ . '/fixtures_non_existent']);
        $classes = $classMap->getClasses();

        $this->assertSame(
            [
                ReflectionTestClass::class,
                TargetMethod::class,
                TargetProperty::class,
                TargetClass::class,
                TestClass2::class,
                TestClass1::class,
            ],
            $classes,
        );
    }
}
