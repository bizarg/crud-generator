<?php

namespace Bizarg\crud\src;

/**
 * Class Config
 * @package Bizarg\crud\src
 */
class Config
{
    /**
     * @var array|null
     */
    private ?array $config;

    /**
     * Config constructor.
     */
    public function __construct()
    {
        $this->config = config('crud-generator') ?? include __DIR__ . '/../config/crud-generator.php';
    }

    /**
     * @return string
     */
    public function namespace(): string
    {
        return $this->config['namespace'];
    }

    /**
     * @return string
     */
    public function domainPath(): string
    {
        return $this->pathWithNamespace($this->config['path']['domain']);
    }

    /**
     * @return string
     */
    public function commandPath(): string
    {
        return $this->pathWithNamespace($this->config['path']['command']);
    }

    /**
     * @return string
     */
    public function repositoryPath(): string
    {
        return $this->pathWithNamespace($this->config['path']['repository']);
    }

    /**
     * @return string
     */
    public function controllerPath(): string
    {
        return $this->pathWithNamespace($this->config['path']['controller']);
    }

    /**
     * @return string
     */
    public function requestPath(): string
    {
        return $this->pathWithNamespace($this->config['path']['request']);
    }

    /**
     * @return string
     */
    public function resourcePath(): string
    {
        return $this->pathWithNamespace($this->config['path']['resource']);
    }

    /**
     * @return string
     */
    public function migratePath(): string
    {
        return $this->trimSlash($this->config['path']['migrate']);
    }

    /**
     * @return string
     */
    public function testPath(): string
    {
        return $this->trimSlash($this->config['path']['test']);
    }

    /**
     * @return string
     */
    public function docPath(): string
    {
        return $this->trimSlash($this->config['path']['doc']);
    }

    /**
     * @return string
     */
    public function stubPath(): string
    {
        return $this->trimSlash($this->config['path']['stub']) ??  __DIR__ . '/../stubs/';
    }

    /**
     * @return string
     */
    public function repositoryFilePrefix(): string
    {
        return $this->config['repositoryFilePrefix'];
    }

    /**
     * @return bool
     */
    public function needGenerateCollection(): bool
    {
        return $this->config['generate']['collection'];
    }

    /**
     * @return bool
     */
    public function needDeclare(): bool
    {
        return $this->config['path']['declare'];
    }

    /**
     * @param $namespace
     * @param $path
     * @return string
     */
    protected function pathWithNamespace($path)
    {
        return $this->trimSlash($this->namespace()) . $this->trimSlash($path);
    }

    /**
     * @param string|null $string
     * @return string
     */
    protected function trimSlash(?string $string): ?string
    {
        if (!$string) {
            return null;
        }

        return trim($string, '/') . '/';
    }
}
