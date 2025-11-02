<?php

namespace Ssmith\AttributeCollector;

use Psr\Log\LoggerInterface;
use Ssmith\AttributeCollector\Cache\InMemoryMemoizer;
use Ssmith\AttributeCollector\Cache\Memoizer;
use Ssmith\AttributeCollector\Collection\AttributeCollection;
use Throwable;

class ClassAttributeCollector
{
    public function __construct(
        private readonly ClassMap $classMap,
        private ?Memoizer $memoizer = null,
        private readonly ?LoggerInterface $logger = null,
    ) {
        if ($this->memoizer === null) {
            $this->memoizer = new InMemoryMemoizer();
        }
    }

    /**
     * Collect all TargetClass and TargetMethod attributes from discovered classes
     * and return an AttributeCollection.
     */
    public function buildRegistry(): AttributeRegistry
    {
        $attributeCollection = $this->memoizer->load();
        if (!$attributeCollection) {
            $attributeCollection = new AttributeCollection();

            foreach ($this->classMap->getClasses() as $class) {
                try {
                    $reader = new ClassAttributeReader($class);
                    $reader->read($attributeCollection);
                } catch (Throwable $e) {
                    $this->logger?->error('Error reading class attributes: ' . $e->getMessage());
                }
            }
        }

        $this->memoizer->save($attributeCollection);

        return new AttributeRegistry($attributeCollection);
    }
}
