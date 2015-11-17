<?php

namespace App\Providers;

use App\Repositories\Resources\Users\UsersRepositoryInterface;
use App\TestCase;

class UsersRepositoryServiceProviderTest extends TestCase
{
    /**
     * @test
     */
    public function it_provides_users_repository()
    {
        $this->refreshApplication();
        $provider = new UsersRepositoryServiceProvider($this->app);
        static::assertSame([UsersRepositoryInterface::class], $provider->provides());
    }

    /**
     * @test
     */
    public function it_registers_users_repository()
    {
        $this->refreshApplication();
        $provider = new UsersRepositoryServiceProvider($this->app);
        $provider->register();
        static::assertInstanceOf(UsersRepositoryInterface::class, $this->app->make(UsersRepositoryInterface::class));
    }
}
