<?php

declare(strict_types=1);

namespace Ssmith\AttributeCollector;

interface ClassMap
{
    /**
     * @return list<class-string>
     */
    public function getClasses(): array;
}
