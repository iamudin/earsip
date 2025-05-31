<?php
namespace Leazycms\EArsip;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Leazycms\EArsip\Middleware\EArsipMiddleware;

class EArsipServiceProvider extends ServiceProvider
{
    protected function registerRoutes()
    {
        if(config('app.sub_app_enabled')){
        Route::middleware(['web','admin.earsip'])
        ->group(function () {
            $this->loadRoutesFrom(__DIR__.'/routes/admin.php');
        });

        Route::middleware(['web'])
        ->domain(parse_url(config('earsip.url'), PHP_URL_HOST))
        ->group(function () {
            $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        });
    }
    }
    protected function registerResources()
    {
        $this->loadViewsFrom(__DIR__ . '/views', 'earsip');
    }

    protected function registerMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . "/database/migrations");
    }


    public function boot(Router $router)
    {
        $router->aliasMiddleware('admin.earsip', EArsipMiddleware::class);
        $this->registerResources();
        $this->registerMigrations();
        $this->add_extension();
        $this->registerRoutes();
    }
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . "/config/config.php", "earsip");
        $this->registerFunctions();

    }
     protected function registerFunctions()
    {
        require_once(__DIR__ . "/Helpers/functions.php");
    }
    function add_extension()
    {
        if (!collect(config('modules.extension_module'))->where('path','earsip')->count()) {
            add_extension(config('earsip'));
        }
    }
}
