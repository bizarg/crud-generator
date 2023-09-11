<?php

namespace Bizarg\Crud;

class CustomTemplate
{
    public function __construct(
        private string $path,
        private string $filename,
        private string $stub
    ) {
    }

    public function path(): string
    {
        return $this->path;
    }

    public function filename(): string
    {
        return $this->filename;
    }

    public function stub(): string
    {
        return $this->stub;
    }
}
