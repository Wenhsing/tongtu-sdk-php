<?php

namespace Wenhsing\Tongtu;

use Illuminate\Support\ServiceProvider;

class LaravelServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom($this->configPath(), 'tongtu');

        $this->app->singleton('tongtu', function ($app) {
            return new Tongtu($this->ttConfig());
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([$this->configPath() => config_path('tongtu.php')], 'wenhsing-tongtu');
        }
    }

    public function configPath()
    {
        return __DIR__.'/../config/config.php';
    }

    public function ttConfig()
    {
        $config = $this->app['config']->get('tongtu');
        foreach (['enable', 'app_key', 'app_secret'] as $v) {
            if (!isset($config[$v])) {
                throw new \RuntimeException("Config [$v] does not exists.");
            }
        }
        return new Config($config);
    }
}
