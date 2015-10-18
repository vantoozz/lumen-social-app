<?php namespace App\Providers;

use App\Repositories\Users\UsersDatabaseRepository;
use App\Repositories\Users\UsersRepositoryInterface;
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
            UsersRepositoryInterface::class,
            UsersDatabaseRepository::class
        );
    }
}
