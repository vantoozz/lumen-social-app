<?php

namespace App\Providers;

use App\Auth\DbUserProvider;
use App\Repositories\Resources\Users\UsersRepositoryInterface;
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
        $auth = $this->app->make('auth');
        $auth->extend(
            'db',
            function () {
                /** @var UsersRepositoryInterface $usersRepository */
                $usersRepository = app(UsersRepositoryInterface::class);

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
