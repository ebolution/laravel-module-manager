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

    public function publishTests(string $testPath, string $moduleFolder): void
    {
        $tests = $this->discoverTests($testPath, $moduleFolder);
        $this->publishes($tests, 'laravel-assets');
    }

    private function discoverTests(string $testPath, string $moduleFolder): array
    {
        $tests = [];
        $absolute_test_path = realpath($testPath);
        $test_path_length = strlen($absolute_test_path);
        $last_folder_length = strlen(basename($absolute_test_path));

        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($absolute_test_path));
        $files = array_reverse(iterator_to_array($iterator));

        foreach ($files as $file) {
            $real_path = $file->getRealPath();
            if ($file->isFile() and $file->getExtension() === 'php') {
                $relative_file_path = substr($real_path, $test_path_length - $last_folder_length);
                $test_file_name = basename($relative_file_path);
                $test_file_folder = dirname($relative_file_path);
                $tests[$real_path] = base_path(
                    $test_file_folder . DIRECTORY_SEPARATOR .
                    $moduleFolder . DIRECTORY_SEPARATOR .
                    $test_file_name
                );
            }
        }

        return $tests;
    }
}
