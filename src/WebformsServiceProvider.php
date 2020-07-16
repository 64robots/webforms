<?php

namespace R64\Webforms;

use Illuminate\Support\ServiceProvider;

class WebformsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/webforms.php' => config_path('webforms.php'),
            ], 'config');

            $this->publishes([
                __DIR__.'/../resources/views' => base_path('resources/views/vendor/webforms'),
            ], 'views');

            if (! class_exists('CreatePackageTable')) {
                $this->publishes([
                    __DIR__ . '/../database/migrations/create_webforms_table.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_webforms_table.php'),
                ], 'migrations');
            }
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/webforms.php', 'webforms');
    }
}
