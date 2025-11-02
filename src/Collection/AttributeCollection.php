<?php

namespace Ssmith\AttributeCollector\Collection;

use Countable;
use ReflectionAttribute;
use Ssmith\AttributeCollector\AttributeDetails;

/**
 * Stores collected attributes for classes and methods.
 */
final class AttributeCollection implements Countable
{
    /**
     * @param array<class-string, array<AttributeDetails>> $attributes
     */
    public function __construct(private array $attributes = []) {}

    public function addClassAttributes(string $class, ReflectionAttribute $attribute): void
    {
        $instance = $attribute->newInstance();

        $this->attributes[$instance::class][] = new AttributeDetails(
            $instance,
            AttributeDetails::CLASS_KIND,
            ['class' => $class],
        );
    }

    public function addMethodAttributes(string $class, string $method, ReflectionAttribute $attribute): void
    {
        $instance = $attribute->newInstance();

        $this->attributes[$instance::class][] = new AttributeDetails(
            $instance,
            AttributeDetails::METHOD_KIND,
            [
                'class' => $class,
                'method' => $method,
            ],
        );
    }

    public function addPropertyAttributes(string $class, string $property, ReflectionAttribute $attribute): void
    {
        $instance = $attribute->newInstance();

        $this->attributes[$instance::class][] = new AttributeDetails(
            $instance,
            AttributeDetails::PROPERTY_KIND,
            [
                'class' => $class,
                'property' => $property,
            ],
        );
    }

    public function count(): int
    {
        return count($this->attributes);
    }

    /**
     * @return array<class-string, array<AttributeDetails>>
     */
    public function all(): array
    {
        return $this->attributes;
    }

    public function clear(): void
    {
        $this->attributes = [];
    }
}
