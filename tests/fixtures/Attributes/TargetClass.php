<?php

namespace Ssmith\AttributeCollector\Tests\fixtures\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class TargetClass
{
    public function __construct()
    {
    }
}
