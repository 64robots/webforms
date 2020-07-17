<?php

namespace R64\Webforms;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use R64\Webforms\Http\Controllers\SectionController;

class WebformsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/webforms.php' => config_path('webforms.php'),
            ], 'config');

            if (! class_exists('CreateWebformsTables')) {
                $this->publishes([
                    __DIR__ . '/../database/migrations/create_webforms_tables.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_webforms_tables.php'),
                ], 'migrations');
            }
        }

        Route::macro('webforms', function (string $prefix) {
            Route::prefix($prefix)->group(function () {
                Route::get('/sections', [SectionController::class, 'index']);
            });
        });
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/webforms.php', 'webforms');
    }
}
