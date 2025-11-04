<?php

namespace Ssmith\AttributeCollector;

use Countable;
use Generator;
use Ssmith\AttributeCollector\Collection\AttributeCollection;

readonly class AttributeRegistry implements Countable
{
    public function __construct(private AttributeCollection $collection) {}

    /**
     * @param class-string $attribute
     * @return Generator<AttributeDetails>
     */
    public function forAttribute(string $attribute): Generator
    {
        foreach ($this->collection->all()[$attribute] ?? [] as $attributeDetails) {
            yield $attributeDetails;
        };
    }

    /**
     * @param class-string $class
     * @return Generator<AttributeDetails>
     */
    public function forClass(string $class): Generator
    {
        foreach ($this->collection->all() as $attributeSet) {
            foreach ($attributeSet as $attributeDetails) {
                if ($attributeDetails->kind === AttributeDetails::CLASS_KIND
                    && $attributeDetails->meta['class'] === $class
                ) {
                    yield $attributeDetails;
                }
            }
        }
    }

    /**
     * @param class-string $class
     * @param string $method
     * @return Generator<AttributeDetails>
     */
    public function forMethod(string $class, string $method): Generator
    {
        foreach ($this->collection->all() as $attributeSet) {
            foreach ($attributeSet as $attributeDetails) {
                if ($attributeDetails->kind === AttributeDetails::METHOD_KIND
                    && $attributeDetails->meta['class'] === $class
                    && $attributeDetails->meta['method'] === $method
                ) {
                    yield $attributeDetails;
                }
            }
        }
    }

    /**
     * @param class-string $class
     * @param string $property
     * @return Generator<AttributeDetails>
     */
    public function forProperty(string $class, string $property): Generator
    {
        foreach ($this->collection->all() as $attributeSet) {
            foreach ($attributeSet as $attributeDetails) {
                if ($attributeDetails->kind === AttributeDetails::PROPERTY_KIND
                    && $attributeDetails->meta['class'] === $class
                    && $attributeDetails->meta['property'] === $property
                ) {
                    yield $attributeDetails;
                }
            }
        }
    }

    public function count(): int
    {
        return $this->collection->count();
    }
}
