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
    }
}
