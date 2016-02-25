<?php


namespace App\Providers;

use App\Miners\UserStatus\UserStatusMinerInterface;
use App\TestCase;

class UserStatusMinerServiceProviderTest extends TestCase
{

    /**
     * @test
     */
    public function it_provides_user_status_miner()
    {
        $this->refreshApplication();
        $provider = new UserStatusMinerServiceProvider($this->app);
        static::assertSame([UserStatusMinerInterface::class], $provider->provides());
    }

    /**
     * @test
     */
    public function it_registers_users_repository()
    {
        $this->refreshApplication();
        $provider = new UserStatusMinerServiceProvider($this->app);
        $provider->register();
        static::assertInstanceOf(UserStatusMinerInterface::class, $this->app->make(UserStatusMinerInterface::class));
    }
}
