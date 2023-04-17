<?php
/**
 * @category  Ebolution
 * @package   Ebolution/ModuleManager
 * @author    Manuel GARCÍA SOLIPA <manuel.garcia@ebolution.com>
 * @copyright 2023 Avanzed Cloud Develop S.L
 * @license   MIT - https://www.ebolution.com/
 */

namespace Ebolution\ModuleManager\Infrastructure\ServiceProviders;

use Illuminate\Support\ServiceProvider;

final class MigrationServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if (app()->runningInConsole()) {
            $this->registerMigrations();

            $this->publishes([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ], 'modules-migrations');

        }
    }

    protected function registerMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}