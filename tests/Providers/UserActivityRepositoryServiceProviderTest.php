<?php

namespace App\Providers;

use App\Repositories\UserActivity\UserActivityRepositoryInterface;
use App\TestCase;

class UserActivityRepositoryServiceProviderTest extends TestCase
{
    /**
     * @test
     */
    public function it_provides_user_activity_repository()
    {
        $this->refreshApplication();
        $provider = new UserActivityRepositoryServiceProvider($this->app);
        static::assertSame([UserActivityRepositoryInterface::class], $provider->provides());
    }

    /**
     * @test
     */
    public function it_registers_user_activity_repository()
    {
        $this->refreshApplication();
        $provider = new UserActivityRepositoryServiceProvider($this->app);
        $provider->register();
        static::assertInstanceOf(
            UserActivityRepositoryInterface::class,
            $this->app->make(UserActivityRepositoryInterface::class)
        );
    }
}
