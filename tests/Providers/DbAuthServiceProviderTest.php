<?php

namespace App\Providers;

use App\TestCase;
use Illuminate\Auth\AuthManager;
use Illuminate\Auth\Guard;

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
        static::assertInstanceOf(Guard::class, $auth->driver());
    }
}
