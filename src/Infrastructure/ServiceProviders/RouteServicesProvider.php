<?php
/**
 * @category  Ebolution
 * @package   Ebolution/__MODULE__
 * @author    Manuel GARCÍA SOLIPA <manuel.garcia@ebolution.com>
 * @copyright 2023 Avanzed Cloud Develop S.L
 * @license   MIT - https://www.ebolution.com/
 */

namespace Ebolution\ModuleManager\Infrastructure\ServiceProviders;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as CoreRouteServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServicesProvider extends CoreRouteServiceProvider
{
    const BASE_PATH = __DIR__;

    protected bool $loadApi = true;
    protected bool $loadWeb = false;

    public function boot()
    {
        $this->loadApi();
    }

    private function loadApi()
    {
        if (! $this->loadApi) {
            return;
        }

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(static::BASE_PATH . DIRECTORY_SEPARATOR . '../Routes/Api.php');
        });
    }

    private function loadWeb()
    {
        if (! $this->loadWeb) {
            return;
        }

        $this->routes(function () {
            require_once(static::BASE_PATH . DIRECTORY_SEPARATOR . '../Routes/Web.php');
        });
    }
}
