<?php
/**
 * @category  Ebolution
 * @package   Ebolution/__MODULE__
 * @author    Manuel GARCÍA SOLIPA <manuel.garcia@ebolution.com>
 * @copyright 2023 Avanzed Cloud Develop S.L
 * @license   MIT - https://www.ebolution.com/
 */

namespace Ebolution\ModuleManager\Infrastructure;

use Illuminate\Console\Application as Artisan;
use Illuminate\Support\AggregateServiceProvider;

class ServicesProvider extends AggregateServiceProvider
{
    const BASE_DIR = __DIR__;

    protected function registerMigrations(): void
    {
        $this->loadMigrationsFrom(static::BASE_DIR . '/../../database/migrations');
    }

    /**
     * Register the package's custom Artisan commands.
     *
     * @param  array|mixed  $commands
     * @return void
     */
    public function commands($commands): void
    {
        $commands = is_array($commands) ? $commands : func_get_args();

        Artisan::starting(function ($artisan) use ($commands) {
            $artisan->resolveCommands($commands);
        });
    }
}