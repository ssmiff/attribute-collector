<?php

namespace Ssmith\AttributeCollector;

use Countable;
use Ssmith\AttributeCollector\Collection\AttributeCollection;

readonly class AttributeRegistry implements Countable
{
    public function __construct(private AttributeCollection $collection) {}

    /**
     * @param class-string $attribute
     * @return array<AttributeDetails>
     */
    public function forAttribute(string $attribute): array
    {
        return $this->collection->all()[$attribute] ?? [];
    }

    /**
     * @param class-string $class
     * @return array<AttributeDetails>
     */
    public function forClass(string $class): array
    {
        $filteredAttributes = [];

        foreach ($this->collection->all() as $attributeSet) {
            foreach ($attributeSet as $attributeDetails) {
                if ($attributeDetails->kind === AttributeDetails::CLASS_KIND
                    && $attributeDetails->meta['class'] === $class
                ) {
                    $filteredAttributes[] = $attributeDetails;
                }
            }
        }

        return $filteredAttributes;
    }

    /**
     * @param class-string $class
     * @param string $method
     * @return array<AttributeDetails>
     */
    public function forMethod(string $class, string $method): array
    {
        $filteredAttributes = [];

        foreach ($this->collection->all() as $attributeSet) {
            foreach ($attributeSet as $attributeDetails) {
                if ($attributeDetails->kind === AttributeDetails::METHOD_KIND
                    && $attributeDetails->meta['class'] === $class
                    && $attributeDetails->meta['method'] === $method
                ) {
                    $filteredAttributes[] = $attributeDetails;
                }
            }
        }

        return $filteredAttributes;
    }

    /**
     * @param class-string $class
     * @param string $property
     * @return array<AttributeDetails>
     */
    public function forProperty(string $class, string $property): array
    {
        $filteredAttributes = [];

        foreach ($this->collection->all() as $attributeSet) {
            foreach ($attributeSet as $attributeDetails) {
                if ($attributeDetails->kind === AttributeDetails::PROPERTY_KIND
                    && $attributeDetails->meta['class'] === $class
                    && $attributeDetails->meta['property'] === $property
                ) {
                    $filteredAttributes[] = $attributeDetails;
                }
            }
        }

        return $filteredAttributes;
    }

    public function count(): int
    {
        return $this->collection->count();
    }
}
