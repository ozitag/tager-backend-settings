<?php

namespace OZiTAG\Tager\Backend\Settings;

use Illuminate\Support\ServiceProvider;
use OZiTAG\Tager\Backend\Rbac\TagerScopes;
use OZiTAG\Tager\Backend\Settings\Console\FlushSettingsCommand;
use OZiTAG\Tager\Backend\Settings\Enums\SettingScope;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('settings', function () {
            return TagerSettings();
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

        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'tager-settings');
        $this->loadMigrationsFrom(__DIR__ . '/../migrations');

        if ($this->app->runningInConsole()) {
            $this->commands([
                FlushSettingsCommand::class,
            ]);
        }

        $this->publishes([
            __DIR__ . '/../config.php' => config_path('tager-settings.php'),
        ]);

        TagerScopes::registerGroup(__('tager-settings::scopes.group'), [
            SettingScope::View => __('tager-settings::scopes.view_settings'),
            SettingScope::Edit => __('tager-settings::scopes.edit_settings')
        ]);
    }
}
