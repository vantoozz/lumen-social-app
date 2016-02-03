<?php

namespace App\Providers;

use App\TestCase;
use Illuminate\Auth\AuthManager;
use Illuminate\Auth\Guard;
use Illuminate\Auth\SessionGuard;
use Illuminate\Contracts\Auth\StatefulGuard;

class DbAuthServiceProviderTest extends TestCase
{
    /**
     * @test
     */
    public function it_registers_database_auth_driver()
    {
        $this->refreshApplication();
        $provider = new DbAuthServiceProvider($this->app);
        $provider->boot();

        /** @var AuthManager $auth */
        $auth = $this->app->make('auth');
        static::assertSame('db', $auth->getDefaultDriver());
        static::assertInstanceOf(SessionGuard::class, $auth->guard());
    }


    /**
     * @test
     */
    public function it_registers_stateful_guard_singleton()
    {
        $this->refreshApplication();
        $provider = new DbAuthServiceProvider($this->app);
        $provider->boot();

        /** @var StatefulGuard $guard */
        $guard = $this->app->make(StatefulGuard::class);
        static::assertInstanceOf(StatefulGuard::class, $guard);
    }
}
