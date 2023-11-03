<?php

namespace Bizarg\Crud;

class Config
{
    private ?array $config;

    public function __construct()
    {
        $this->config = config('crud-generator') ?? include __DIR__ . '/../config/crud-generator.php';
    }

    public function stubPath(): string
    {
        return '/' . $this->trimSlash($this->config['paths']['stub']) ??  __DIR__ . '/../stubs/';
    }

    /**
     * @return CustomTemplate[]
     */
    public function templates(): array
    {
        $templates = [];

        foreach ($this->config['templates'] ?? [] as $tempalte) {
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
