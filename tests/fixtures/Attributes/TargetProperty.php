<?php

namespace Ssmith\AttributeCollector\Tests\fixtures\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
readonly class TargetProperty
{
    public function __construct()
    {
    }
}
