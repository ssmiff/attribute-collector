<?php

declare(strict_types=1);

namespace Cache;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Ssmith\AttributeCollector\Cache\FileMemoizer;
use Ssmith\AttributeCollector\Collection\AttributeCollection;
use Ssmith\AttributeCollector\Tests\fixtures\ReflectionTestClass;

class FileMemoizeTest extends TestCase
{
    #[Test]
    public function test_can_memoize_without_directory(): void
    {
        $collection = new AttributeCollection();

        $reflectionClass = new ReflectionClass(ReflectionTestClass::class);
        $attribute = $reflectionClass->getAttributes()[0];
        $collection->addClassAttributes('className', $attribute);

        $memoize = new FileMemoizer('some-filename');

        $memoize->save($collection);

        $expectedFilepath = sys_get_temp_dir()
            . DIRECTORY_SEPARATOR
            . 'attribute-tests-cache'
            . DIRECTORY_SEPARATOR
            . 'some-filename.ser';

        $this->assertFileExists($expectedFilepath);

        $unserializedCollection = unserialize(file_get_contents($expectedFilepath));

        $this->assertInstanceOf(AttributeCollection::class, $unserializedCollection);
    }

    #[Test]
    public function test_can_create_directory(): void
    {
        $directory = sys_get_temp_dir()
            . DIRECTORY_SEPARATOR
            . 'attribute-tests-cache-not-exists'
            . DIRECTORY_SEPARATOR
            . 'some-filename.ser';

        $this->assertDirectoryDoesNotExist($directory);

        new FileMemoizer('some-filename', $directory);

        $this->assertDirectoryExists($directory);

        @rmdir($directory);

        $this->assertDirectoryDoesNotExist($directory);
    }

    #[Test]
    public function test_can_memoize_with_directory(): void
    {
        $collection = new AttributeCollection();

        $reflectionClass = new ReflectionClass(ReflectionTestClass::class);
        $attribute = $reflectionClass->getAttributes()[0];
        $collection->addClassAttributes('className', $attribute);

        $memoize = new FileMemoizer('some-filename', __DIR__);

        $memoize->save($collection);

        $expectedFilepath = __DIR__
            . DIRECTORY_SEPARATOR
            . 'some-filename.ser';

        $this->assertFileExists($expectedFilepath);

        $unserializedCollection = unserialize(file_get_contents($expectedFilepath));

        $this->assertInstanceOf(AttributeCollection::class, $unserializedCollection);
    }

    #[Test]
    public function test_returns_null_when_not_file(): void
    {
        // Our own directory should not be a file
        $memoize = new FileMemoizer('Cache', __DIR__ . DIRECTORY_SEPARATOR . '..');

        $this->assertNull($memoize->load());
    }

    #[Test]
    public function test_returns_null_when_not_readable_file(): void
    {
        $memoize = new FileMemoizer('cache', __DIR__);

        $this->assertNull($memoize->load());
    }

    #[Test]
    public function test_returns_null_when_file_empty(): void
    {
        $memoize = new FileMemoizer('some-filename');

        $expectedFilepath = sys_get_temp_dir()
            . DIRECTORY_SEPARATOR
            . 'attribute-tests-cache'
            . DIRECTORY_SEPARATOR
            . 'some-filename.ser';

        file_put_contents($expectedFilepath, '');

        $response = $memoize->load();

        unlink($expectedFilepath);

        $this->assertNull($response);
    }

    #[Test]
    public function test_returns_attribute_collection(): void
    {
        $collection = new AttributeCollection();

        $reflectionClass = new ReflectionClass(ReflectionTestClass::class);
        $attribute = $reflectionClass->getAttributes()[0];
        $collection->addClassAttributes('className', $attribute);

        $memoize = new FileMemoizer('some-filename', __DIR__);
        $memoize->save($collection);

        $this->assertEquals($collection->all(), $memoize->load()->all());
    }
}
