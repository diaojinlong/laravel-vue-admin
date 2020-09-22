<?php

namespace DiaoJinLong\LaravelVueAdmin;

use Illuminate\Support\ServiceProvider;
use DiaoJinLong\LaravelVueAdmin\Middleware\Auth;
use Godruoyi\Snowflake\Snowflake;
use Godruoyi\Snowflake\LaravelSequenceResolver;

class LaravelVueAdminServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('laravel-vue-admin', function ($app) {
            return new LaravelVueAdmin($app);
        });
        $this->app->singleton('snowflake', function () {
            $snowflake = (new Snowflake())->setStartTimeStamp(
                strtotime(config('laravel-vue-admin.snowflake.start_time_stamp'))*1000
            );
            if(strtolower(config('cache.default')) == 'redis'){
                $snowflake->setSequenceResolver(new LaravelSequenceResolver($this->app->get('cache')->store()));
            }
            return $snowflake;
        });
    }

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/Routes/admin.php');
        $this->loadMigrationsFrom(__DIR__.'/Database/Migrations');
        $this->addMiddlewareAlias('laravel-vue-admin.auth', Auth::class);
        $this->publishes([
            __DIR__ . '/Config/laravel-vue-admin.php' => config_path('laravel-vue-admin.php'),
        ]);
    }

    protected function addMiddlewareAlias($name, $class)
    {
        $router = $this->app['router'];
        if (method_exists($router, 'aliasMiddleware')) {
            return $router->aliasMiddleware($name, $class);
        }
        return $router->middleware($name, $class);
    }
}
