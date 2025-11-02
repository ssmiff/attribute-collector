<?php

declare(strict_types=1);

namespace Ssmith\AttributeCollector\Tests\fixtures;

use Ssmith\AttributeCollector\Tests\fixtures\Attributes\TargetClass;
use Ssmith\AttributeCollector\Tests\fixtures\Attributes\TargetMethod;
use Ssmith\AttributeCollector\Tests\fixtures\Attributes\TargetProperty;

#[TargetClass]
readonly class ReflectionTestClass
{
    #[TargetProperty]
    public int $age;

    public function __construct(
        #[TargetProperty]
        public string $name,
        int $age,
    ) {
        $this->age = $age;
    }

    #[TargetMethod]
    public function testMethod(): void {}
}
