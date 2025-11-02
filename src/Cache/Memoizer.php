<?php

namespace Ssmith\AttributeCollector\Cache;

use Ssmith\AttributeCollector\Collection\AttributeCollection;

interface Memoizer
{
    public function save(AttributeCollection $collection);

    public function load(): ?AttributeCollection;
}
