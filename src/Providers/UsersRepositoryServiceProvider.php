<?php declare(strict_types = 1);

namespace App\Providers;

use App\Hydrators\User\DatabaseUserHydrator;
use App\Repositories\Resources\Users\DatabaseUsersRepository;
use App\Repositories\Resources\Users\UsersRepositoryInterface;
use Illuminate\Database\Connection;
use Illuminate\Support\ServiceProvider;

/**
 * Class UsersRepositoryServiceProvider
 * @package App\Providers
 */
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
        $this->app->singleton(UsersRepositoryInterface::class, function () {
            /** @var Connection $db */
            $db = $this->app->make(DbConnectionServiceProvider::SERVICE_NAME);
            $hydrator = new DatabaseUserHydrator;

            return new DatabaseUsersRepository($db, $hydrator);
        });
    }
}
