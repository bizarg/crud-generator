<?php

namespace Bizarg\Crud;

use Illuminate\Support\ServiceProvider;

class CrudServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ApiCrudGenerator::class,
            ]);
        }


        if (method_exists($this, 'publishes')) {
            $this->publishes([
                __DIR__ . '/../stubs' => base_path('/resources/stubs/'),
            ], 'crud-generator-stubs');
            $this->publishes([
                __DIR__ . '/../config/crud-generator.php' => $this->configPath('crud-generator.php'),
            ], 'crud-generator-config');
        }
    }

    private function configPath(string $path = ''): string
    {
        return function_exists('config_path')
            ? config_path($path)
            : app()->basePath() . DIRECTORY_SEPARATOR . 'config' . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

