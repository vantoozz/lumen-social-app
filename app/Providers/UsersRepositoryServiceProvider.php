<?php namespace App\Providers;

use App\Repositories\Users\DatabaseUsersRepository;
use App\Repositories\Users\UsersRepositoryInterface;
use Illuminate\Database\Connection;
use Illuminate\Support\ServiceProvider;

class UsersRepositoryServiceProvider extends ServiceProvider
{
    /**
     * @var bool
     */
    protected $defer = false;

    /**
     * @return array
     */
    public function provides()
    {
        return [UsersRepositoryInterface::class];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            UsersRepositoryInterface::class,
            function () {
                /** @var Connection $db */
                $db = app(DbConnectionServiceProvider::SERVICE_NAME);

                return new DatabaseUsersRepository($db);
            }
        );
    }
}
