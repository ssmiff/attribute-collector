<?php

namespace Ssmith\AttributeCollector\Cache;

use Ssmith\AttributeCollector\Collection\AttributeCollection;

class InMemoryMemoizer implements Memoizer
{
    private array $cache = [];

    public function save(AttributeCollection $collection): void
    {
        $this->cache = $collection->all();
    }

    public function load(): ?AttributeCollection
    {
        if (empty($this->cache)) {
            return null;
        }

        return new AttributeCollection($this->cache);
    }
}
