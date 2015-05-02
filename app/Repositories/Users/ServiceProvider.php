<?php

namespace App\Repositories\Users;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

/**
 * Class ServiceProvider
 * @package App\Repositories\Users
 */
class ServiceProvider extends BaseServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            'App\Repositories\Users\UsersRepositoryInterface',
            'App\Repositories\Users\UsersDatabaseRepository'
        );
    }
}