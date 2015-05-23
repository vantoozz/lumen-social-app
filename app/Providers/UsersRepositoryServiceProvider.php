<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class UsersRepositoryServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
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
