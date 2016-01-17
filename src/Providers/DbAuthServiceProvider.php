<?php

namespace App\Providers;

use App\Auth\DbUserProvider;
use App\Repositories\Resources\Users\UsersRepositoryInterface;
use Illuminate\Auth\AuthManager;
use Illuminate\Auth\SessionGuard;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Session\SessionInterface;
use Illuminate\Session\SessionManager;
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
        $auth = $this->app->make('auth');
        $auth->extend('db', function () {
            /** @var UsersRepositoryInterface $usersRepository */
            $usersRepository = $this->app->make(UsersRepositoryInterface::class);
            /** @var SessionManager $sessionManager */
            $sessionManager = $this->app->make('session');
            /** @var $session SessionInterface */
            $session = $sessionManager->driver();

            return new SessionGuard('db', new DbUserProvider($usersRepository), $session);
        });

        $this->app->singleton(StatefulGuard::class, function () {
            return $this->app->make(Guard::class);
        });
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
