<?php

namespace Bizarg\Crud;

/**
 * Class CustomTemplate
 * @package Bizarg\Crud
 */
class CustomTemplate
{
    /**
     * @var string
     */
    private string $path;
    /**
     * @var string
     */
    private string $filename;
    /**
     * @var string
     */
    private string $stub;

    /**
     * CustomTemplate constructor.
     * @param string $path
     * @param string $filename
     * @param string $stub
     */
    public function __construct(
        string $path,
        string $filename,
        string $stub
    ) {
        $this->path = $path;
        $this->filename = $filename;
        $this->stub = $stub;
    }

    /**
     * @return string
     */
    public function path(): string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function filename(): string
    {
        return $this->filename;
    }

    /**
     * @return string
     */
    public function stub(): string
    {
        return $this->stub;
    }
}
