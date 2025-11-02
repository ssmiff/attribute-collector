<?php

namespace Ssmith\AttributeCollector\Tests\fixtures\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
readonly class TargetMethod
{
    public function __construct()
    {
    }
}
