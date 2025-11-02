<?php

namespace Ssmith\AttributeCollector;

use Composer\ClassMapGenerator\ClassMapGenerator;

final readonly class ComposerClassMap implements ClassMap
{
    /**
     * @var list<string>
     */
    private array $directories;

    public function __construct(array $directories = [])
    {
        $this->directories = array_map(fn (string $directory):string => $directory, $directories);
    }

    /**
     * @return list<class-string>
     */
    public function getClasses(): array
    {
        $generator = new ClassMapGenerator(['php']);
        $generator->avoidDuplicateScans();

        foreach ($this->directories as $dir) {
            try {
                $generator->scanPaths($dir);
            } catch (\Exception $e) {
                // ignore bad directories
                continue;
            }
        }

        return array_filter(
            array_keys($generator->getClassMap()->getMap()),
            fn (string $class):bool => class_exists($class)
        );
    }
}
