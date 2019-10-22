<?php

namespace Huangdijia\WebSoar;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Huangdijia\WebSoar\Console\InstallCommand;
use Illuminate\Session\Middleware\StartSession;
use Huangdijia\WebSoar\Http\Middleware\Authorize;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Huangdijia\WebSoar\OutputModifiers\OutputModifier;
use Huangdijia\WebSoar\Http\Controllers\WebSoarController;

class WebSoarServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/web-soar.php' => config_path('web-soar.php'),
            ], 'config');

            $this->publishes([
                __DIR__.'/../resources/views' => base_path('resources/views/vendor/web-soar'),
            ], 'views');

            $this->publishes([
                __DIR__.'/../public' => public_path('vendor/web-soar'),
            ], 'web-soar-assets');
        }

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'web-soar');

        $this->app->bind(OutputModifier::class, config('web-soar.output_modifier'));

        $this->registerRoutes()
            ->registerWebSoarGate();
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/web-soar.php', 'web-soar');

        $this->commands(InstallCommand::class);
    }

    protected function registerRoutes()
    {
        Route::prefix(config('web-soar.path'))->middleware([
            EncryptCookies::class,
            StartSession::class,
            Authorize::class,
        ])->group(function () {
            Route::get('/', [WebSoarController::class, 'index']);
            Route::post('/', [WebSoarController::class, 'execute']);
        });

        return $this;
    }

    protected function registerWebSoarGate()
    {
        Gate::define('viewWebSoar', function ($user = null) {
            // return app()->environment('local');
            return true;
        });

        return $this;
    }
}
