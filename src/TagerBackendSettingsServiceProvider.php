<?php

namespace OZiTAG\Tager\Backend\Settings;

use Illuminate\Support\ServiceProvider;

class TagerBackendSettingsServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/routes.php');

        $this->publishes([
            __DIR__ . '/../config.php' => config_path('tager-settings.php'),
        ]);
    }
}
