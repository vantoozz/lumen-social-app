<?php namespace App\Providers;

use App\Miners\UserStatus\UserStatusMiner;
use App\Miners\UserStatus\UserStatusMinerInterface;
use App\Repositories\UserActivity\UserActivityRepositoryInterface;
use Illuminate\Support\ServiceProvider;

/**
 * Class UserStatusMinerServiceProvider
 * @package App\Providers
 */
class UserStatusMinerServiceProvider extends ServiceProvider
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
        return [UserStatusMinerInterface::class];
    }

    /**
     * Register any application services.
     * @return void
     */
    public function register()
    {
        $this->app->singleton(UserStatusMinerInterface::class, function () {
            /** @var UserActivityRepositoryInterface $activitiesRepository */
            $activitiesRepository = $this->app->make(UserActivityRepositoryInterface::class);

            return new UserStatusMiner($activitiesRepository);
        });
    }
}
