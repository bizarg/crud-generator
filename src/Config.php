<?php

namespace Bizarg\Crud;

class Config
{
    private ?array $config;

    public function __construct()
    {
        $this->config = config('crud-generator') ?? include __DIR__ . '/../config/crud-generator.php';
    }

    public function namespace(): string
    {
        return $this->config['namespace'];
    }

    public function domainPath(): ?string
    {
        return $this->pathWithNamespace($this->config['paths']['domain']);
    }

    public function commandPath(): ?string
    {
        return $this->pathWithNamespace($this->config['paths']['command']);
    }

    public function repositoryPath(): ?string
    {
        return $this->pathWithNamespace($this->config['paths']['repository']);
    }

    public function controllerPath(): ?string
    {
        return $this->pathWithNamespace($this->config['paths']['controller']);
    }

    public function requestPath(): ?string
    {
        return $this->pathWithNamespace($this->config['paths']['request']);
    }

    public function resourcePath(): ?string
    {
        return $this->pathWithNamespace($this->config['paths']['resource']);
    }

    public function migratePath(): ?string
    {
        return $this->trimSlash($this->config['paths']['migrate']);
    }

    public function testPath(): ?string
    {
        return $this->trimSlash($this->config['paths']['test']);
    }

    public function docPath(): ?string
    {
        return $this->trimSlash($this->config['paths']['doc']);
    }

    public function stubPath(): string
    {
        return '/' . $this->trimSlash($this->config['paths']['stub']) ??  __DIR__ . '/../stubs/';
    }

    public function routePath(): ?string
    {
        if (is_null($this->config['paths']['route'])) {
            return null;
        }
        return '/' . ltrim($this->config['paths']['route'], '/');
    }

    public function repositoryFilePrefix(): ?string
    {
        return $this->config['repositoryFilePrefix'];
    }

    public function needGenerateCollection(): bool
    {
        return $this->config['generate']['collection'];
    }

    public function needDeclare(): bool
    {
        return $this->config['declare'];
    }

    public function pathWithNamespace(?string $path): ?string
    {
        if (is_null($path)) {
            return null;
        }
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

    protected function trimSlash(?string $string): ?string
    {
        if (!$string) {
            return null;
        }

        return trim($string, '/') . '/';
    }
}
