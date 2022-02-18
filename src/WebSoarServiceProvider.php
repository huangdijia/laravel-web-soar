<?php

declare(strict_types=1);
/**
 * This file is part of laravel-web-soar.
 *
 * @link     https://github.com/huangdijia/laravel-web-soar
 * @document https://github.com/huangdijia/laravel-web-soar/blob/2.x/README.md
 * @contact  huangdijia@gmail.com
 */
namespace Huangdijia\WebSoar;

use Guanguans\SoarPHP\Soar;
use Huangdijia\WebSoar\Console\InstallCommand;
use Huangdijia\WebSoar\Console\PublishCommand;
use Huangdijia\WebSoar\Http\Controllers\WebSoarController;
use Huangdijia\WebSoar\Http\Middleware\Authorize;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class WebSoarServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/web-soar.php' => config_path('web-soar.php'),
            ], 'config');

            $this->publishes([
                __DIR__ . '/../resources/views' => base_path('resources/views/vendor/web-soar'),
            ], 'views');

            $this->publishes([
                __DIR__ . '/../public' => public_path('vendor/web-soar'),
            ], 'web-soar-assets');
        }

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'web-soar');

        $this->registerRoutes();
        $this->registerWebSoarGate();
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/web-soar.php', 'web-soar');

        $this->app->when(Soar::class)
            ->needs('$config')
            ->give(function () {
                return collect(config('web-soar'))
                    ->filter(function ($item, $key) {
                        return Str::startsWith($key, '-');
                    })
                    ->all();
            });

        $this->app->singleton(Soar::class);
        $this->app->alias(Soar::class, 'soar');

        $this->commands(InstallCommand::class);
        $this->commands(PublishCommand::class);
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
    }

    protected function registerWebSoarGate()
    {
        Gate::define('viewWebSoar', function ($user = null) {
            return app()->environment('local');
            // return true;
        });
    }
}
