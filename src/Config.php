<?php

namespace Bizarg\Crud;

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
        return $this->pathWithNamespace($this->config['paths']['domain']);
    }

    /**
     * @return string
     */
    public function commandPath(): string
    {
        return $this->pathWithNamespace($this->config['paths']['command']);
    }

    /**
     * @return string
     */
    public function repositoryPath(): string
    {
        return $this->pathWithNamespace($this->config['paths']['repository']);
    }

    /**
     * @return string
     */
    public function controllerPath(): string
    {
        return $this->pathWithNamespace($this->config['paths']['controller']);
    }

    /**
     * @return string
     */
    public function requestPath(): string
    {
        return $this->pathWithNamespace($this->config['paths']['request']);
    }

    /**
     * @return string
     */
    public function resourcePath(): string
    {
        return $this->pathWithNamespace($this->config['paths']['resource']);
    }

    /**
     * @return string
     */
    public function migratePath(): string
    {
        return $this->trimSlash($this->config['paths']['migrate']);
    }

    /**
     * @return string
     */
    public function testPath(): string
    {
        return $this->trimSlash($this->config['paths']['test']);
    }

    /**
     * @return string
     */
    public function docPath(): string
    {
        return $this->trimSlash($this->config['paths']['doc']);
    }

    /**
     * @return string
     */
    public function stubPath(): string
    {
        return '/' . $this->trimSlash($this->config['paths']['stub']) ??  __DIR__ . '/../stubs/';
    }

    /**
     * @return string
     */
    public function routePath(): string
    {
        return '/' . ltrim($this->config['paths']['route'], '/');
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
        return $this->config['declare'];
    }

    /**
     * @param string $path
     * @return string
     */
    public function pathWithNamespace($path)
    {
        return $this->trimSlash($this->namespace()) . $this->trimSlash($path);
    }

    /**
     * @return CustomTemplate[]
     */
    public function custom(): array
    {
        $templates = [];

        foreach ($this->config['custom'] ?? [] as $tempalte) {
            if (isset($tempalte['path']) && isset($tempalte['filename']) && isset($tempalte['stub'])) {
                $templates[] = new CustomTemplate(
                    $tempalte['path'],
                    $tempalte['filename'],
                    $tempalte['stub']
                );
            }
        }

        return $templates;
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
