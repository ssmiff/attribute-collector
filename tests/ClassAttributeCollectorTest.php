<?php

declare(strict_types=1);

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Ssmith\AttributeCollector\Cache\Memoizer;
use Ssmith\AttributeCollector\ClassAttributeCollector;
use Ssmith\AttributeCollector\ClassMap;
use Ssmith\AttributeCollector\Collection\AttributeCollection;
use Ssmith\AttributeCollector\Tests\fixtures\ReflectionTestClass;

#[CoversClass(ClassAttributeCollector::class)]
class ClassAttributeCollectorTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    #[Test]
    public function returns_empty_collection_in_registry_when_no_classes_are_registered(): void
    {
        $mockClassMap = Mockery::mock(
            ClassMap::class,
            fn(MockInterface $mock) => $mock
                ->expects('getClasses')
                ->andReturn(['wrong_class_name']),
        );

        $collector = new ClassAttributeCollector($mockClassMap);

        $registry = $collector->buildRegistry();
        $this->assertCount(0, $registry);
    }

    #[Test]
    public function returns_empty_collection_in_registry_when_no_classes_are_registered_and_logs_if_has_logger(): void
    {
        $mockClassMap = Mockery::mock(
            ClassMap::class,
            fn(MockInterface $mock) => $mock
                ->expects('getClasses')
                ->andReturn(['wrong_class_name']),
        );

        $mockLogger = Mockery::mock(
            LoggerInterface::class,
            fn(MockInterface $mock) => $mock
                ->expects('error')
                ->with('Error reading class attributes: Class "wrong_class_name" does not exist'),
        );

        $collector = new ClassAttributeCollector($mockClassMap, logger: $mockLogger);

        $registry = $collector->buildRegistry();
        $this->assertCount(0, $registry);
    }

    #[Test]
    public function returns_empty_expected_built_collection(): void
    {
        $mockClassMap = Mockery::mock(
            ClassMap::class,
            fn(MockInterface $mock) => $mock
                ->expects('getClasses')
                ->andReturn([ReflectionTestClass::class]),
        );

        $collector = new ClassAttributeCollector($mockClassMap);

        $registry = $collector->buildRegistry();
        $this->assertCount(3, $registry);
    }

    #[Test]
    public function returns_empty_expected_built_collection_and_saves_memoize(): void
    {
        $mockClassMap = Mockery::mock(
            ClassMap::class,
            fn(MockInterface $mock) => $mock
                ->expects('getClasses')
                ->andReturn([ReflectionTestClass::class]),
        );

        $mockMemoizer = Mockery::mock(
            Memoizer::class,
            function (MockInterface $mock) {
                $mock
                    ->expects('load')
                    ->andReturn(null);

                $mock
                    ->expects('save')
                    ->with($this->isInstanceOf(AttributeCollection::class));
            },
        );

        $collector = new ClassAttributeCollector($mockClassMap, $mockMemoizer);

        $registry = $collector->buildRegistry();
        $this->assertCount(3, $registry);
    }

    #[Test]
    public function returns_empty_expected_built_collection_loads_and_saves_as_expected(): void
    {
        $mockClassMap = Mockery::mock(
            ClassMap::class,
            fn(MockInterface $mock) => $mock
                ->expects('getClasses')
                ->never()
        );

        $mockMemoizer = Mockery::mock(
            Memoizer::class,
            function (MockInterface $mock) {
                $collection = new AttributeCollection();

                $mock
                    ->expects('load')
                    ->andReturn($collection);

                $mock
                    ->expects('save')
                    ->withArgs(
                        fn($saveCollection) => $saveCollection == $collection,
                    );
            },
        );

        $collector = new ClassAttributeCollector($mockClassMap, $mockMemoizer);
        $collector->buildRegistry();
    }
}
