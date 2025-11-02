# Attribute Collector

Collect all public class/method/properties attributes into a single registry.

## Usage
There are two built in memoizers, `FileMemoizer` and `InMemoryMemoizer`. These can optionally be used to cache the results of the discovery process.

Example:
```php
use Ssmith\AttributeCollector\Cache\FileMemoizer;
use Ssmith\AttributeCollector\ComposerClassMap;
use Ssmith\AttributeCollector\Tests\fixtures\Attributes\TargetClass;

include __DIR__ . '/vendor/autoload.php';

$classMap = new ComposerClassMap([__DIR__ . '/tests/fixtures']);
$memoize = new InMemoryMemoizer();
$logger = new PrsLogger();
$discovery = new Ssmith\AttributeCollector\ClassAttributeCollector($classMap, $memoize, $logger);

$registry = $discovery->buildRegistry();

$allAttributesByName = $registry->forAttribute(MyAttribute::class);

$allAttributesOnClass = $registry->forClass(MyClass::class);
$allAttributesOnClassMethod = $registry->forMethod(MyClass::class, 'myMethod');
$allAttributesOnClassProperty = $registry->forProperty(MyClass::class, 'myProperty');
$distinctAttributes = $registry->count();
```
