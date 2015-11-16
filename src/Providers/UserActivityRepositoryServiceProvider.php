<?php namespace App\Providers;

use App\Repositories\UserActivity\DatabaseUserActivityRepository;
use App\Repositories\UserActivity\UserActivityRepositoryInterface;
use Illuminate\Database\Connection;
use Illuminate\Support\ServiceProvider;

/**
 * Class UserActivityRepositoryServiceProvider
 * @package App\Providers
 */
class UserActivityRepositoryServiceProvider extends ServiceProvider
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
        return [UserActivityRepositoryInterface::class];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(UserActivityRepositoryInterface::class, function () {
            /** @var Connection $db */
            $db = $this->app->make(DbConnectionServiceProvider::SERVICE_NAME);

            return new DatabaseUserActivityRepository($db);
        });
    }
}
