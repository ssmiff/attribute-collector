<?php

declare(strict_types=1);

namespace Ssmith\AttributeCollector;

use ReflectionAttribute;

final readonly class AttributeDetails {
    public const string CLASS_KIND = 'class';
    public const string METHOD_KIND = 'method';
    public const string PROPERTY_KIND = 'property';

    public function __construct(
        public object $instance,
        public string $kind,
        public array $meta = [],
    ) {}
}
