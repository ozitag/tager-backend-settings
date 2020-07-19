<?php

namespace OZiTAG\Tager\Backend\Settings;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use OZiTAG\Tager\Backend\Settings\Commands\FlushSettingsCommand;

class TagerBackendSettingsServiceProvider extends RouteServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('settings', function () {
            return TagerBackendSettings();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/routes.php');

        $this->loadMigrationsFrom(__DIR__ . '/../migrations');

        if ($this->app->runningInConsole()) {
            $this->commands([
                FlushSettingsCommand::class,
            ]);
        }

        $this->publishes([
            __DIR__ . '/../config.php' => config_path('tager-settings.php'),
        ]);

        parent::boot();
    }
}
