<?php

namespace Ssmith\AttributeCollector\Cache;

use Ssmith\AttributeCollector\Collection\AttributeCollection;

readonly class FileMemoizer implements Memoizer
{
    private string $directory;

    public function __construct(private string $filename, ?string $directory = null)
    {
        $this->directory = $directory ?: sys_get_temp_dir() . DIRECTORY_SEPARATOR;
        if (!is_dir($this->directory)) {
            @mkdir($this->directory, 0777, true);
        }
    }

    public function save(AttributeCollection $collection): void
    {
        @file_put_contents($this->filename(), serialize($collection));
    }

    public function load(): ?AttributeCollection
    {
        $filename = $this->filename();

        if (!is_file($filename) || !is_readable($filename)) {
            return null;
        }

        $contents = @file_get_contents($filename);
        if (empty($contents)) {
            return null;
        }

        return unserialize($contents);
    }

    private function filename(): string
    {
        $safeFilename = preg_replace('/[^A-Za-z0-9_.-]/', '_', (string)$this->filename);

        return $this->directory . DIRECTORY_SEPARATOR . $safeFilename . '.ser';
    }
}
