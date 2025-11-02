<?php

declare(strict_types=1);

namespace Ssmith\AttributeCollector;

use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use Ssmith\AttributeCollector\Collection\AttributeCollection;

final readonly class ClassAttributeReader
{
    /** @var ReflectionClass<object> */
    private ReflectionClass $refClass;

    /**
     * @param class-string|object $object
     * @throws ReflectionException
     */
    public function __construct(string|object $object)
    {
        $this->refClass = new ReflectionClass($object);
    }

    public function read(AttributeCollection $collection): void
    {
        $this->readClassAttributes($collection);
        $this->readPropertyAttributes($collection);
        $this->readMethodAttributes($collection);
    }

    private function readClassAttributes(AttributeCollection $collection): void
    {
        foreach ($this->refClass->getAttributes() as $attr) {
            if ($attr->getName() === 'Attribute') {
                continue;
            }

            $collection->addClassAttributes($this->refClass->getName(), $attr);
        }
    }

    private function readPropertyAttributes(AttributeCollection $collection): void
    {
        foreach ($this->refClass->getProperties() as $prop) {
            foreach ($prop->getAttributes() as $attr) {
                $collection->addPropertyAttributes(
                    $this->refClass->getName(),
                    $prop->getName(),
                    $attr
                );
            }
        }
    }

    private function readMethodAttributes(AttributeCollection $collection): void
    {
        foreach ($this->refClass->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            foreach ($method->getAttributes() as $attr) {
                $collection->addMethodAttributes(
                    $this->refClass->getName(),
                    $method->getName(),
                    $attr
                );
            }
        }
    }
}
