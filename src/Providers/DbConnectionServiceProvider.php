<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class DbConnectionServiceProvider
 * @package App\Providers
 */
class DbConnectionServiceProvider extends ServiceProvider
{

    const SERVICE_NAME = 'db_connection';

    /**
     * @var bool
     */
    protected $defer = false;

    /**
     * @return array
     */
    public function provides()
    {
        return [self::SERVICE_NAME];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(self::SERVICE_NAME, function () {
            return $this->app->make('db')->connection();
        });
    }
}
