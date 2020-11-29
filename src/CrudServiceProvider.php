<?php

namespace Bizarg\Crud;

use Illuminate\Support\ServiceProvider;

/**
 * Class CrudServiceProvider
 * @package Bizarg\Crud
 */
class CrudServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
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

    /**
     * @param string $path
     * @return string
     */
    private function configPath($path = ''): string
    {
        return function_exists('config_path')
            ? config_path($path)
            : app()->basePath() . DIRECTORY_SEPARATOR . 'config' . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

