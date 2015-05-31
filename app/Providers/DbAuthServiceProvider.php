<?php

namespace App\Providers;

use App\Auth\DbUserProvider;
use Illuminate\Auth\AuthManager;
use Illuminate\Support\ServiceProvider;

/**
 * Class DbAuthServiceProvider
 * @package App\Providers
 */
class DbAuthServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        /** @var AuthManager $auth */
        $auth = $this->app['auth'];
        $auth->extend(
            'db',
            function () {
                /** @var \App\Repositories\Users\UsersRepositoryInterface $usersRepository */
                $usersRepository = app()->make('App\Repositories\Users\UsersRepositoryInterface');

                return new DbUserProvider($usersRepository);
            }
        );
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
