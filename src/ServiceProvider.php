<?php

/*
 * This file is part of the overtrue/laravel-baidu.
 *
 * (c) overtrue <i@overtrue.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Liqunx\LaravelBaidu;

use Liqunx\Baidu\Ai\Application as Aip;
use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Laravel\Lumen\Application as LumenApplication;

/**
 * Class ServiceProvider.
 *
 * @author overtrue <i@overtrue.me>
 */
class ServiceProvider extends LaravelServiceProvider
{
    /**
     * Boot the provider.
     */
    public function boot()
    {
    }

    /**
     * Setup the config.
     */
    protected function setupConfig()
    {
        $source = realpath(__DIR__ . '/config.php');

        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([$source => config_path('baidu.php')], 'laravel-baidu');
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('baidu');
        }

        $this->mergeConfigFrom($source, 'baidu');
    }

    /**
     * Register the provider.
     */
    public function register()
    {
        $this->setupConfig();

        $apps = [
            'aip' => Aip::class,
        ];

        foreach ($apps as $name => $class) {
            if (empty(config('baidu.'.$name))) {
                continue;
            }

            if ($config = config('baidu.route.'.$name)) {
                $this->getRouter()->group($config['attributes'], function ($router) use ($config) {
                    $router->post($config['uri'], $config['action']);
                });
            }

            if (!empty(config('baidu.'.$name.'.app_id')) || !empty(config('baidu.'.$name.'.corp_id'))) {
                $accounts = [
                    'default' => config('baidu.'.$name),
                ];
                config(['baidu.'.$name.'.default' => $accounts['default']]);
            } else {
                $accounts = config('baidu.'.$name);
            }
            foreach ($accounts as $account => $config) {
                $this->app->singleton("baidu.{$name}.{$account}", function ($laravelApp) use ($name, $account, $config, $class) {
                    $app = new $class(array_merge(config('baidu.defaults', []), $config));
                    if (config('baidu.defaults.use_laravel_cache')) {
                        $app['cache'] = new CacheBridge($laravelApp['cache.store']);
                    }
                    $app['request'] = $laravelApp['request'];

                    return $app;
                });
            }
            $this->app->alias("baidu.{$name}.default", 'baidu.'.$name);
            $this->app->alias('baidu.'.$name, $class);
        }
    }

    protected function getRouter()
    {
        if ($this->app instanceof LumenApplication && !class_exists('Laravel\Lumen\Routing\Router')) {
            return $this->app;
        }

        return $this->app->router;
    }
}
