<?php declare(strict_types = 1);

namespace App\Providers;

use App\TestCase;
use Illuminate\Database\Connection;

class DbConnectionServiceProviderTest extends TestCase
{
    /**
     * @test
     */
    public function it_provides_db_connection()
    {
        $this->refreshApplication();
        $provider = new DbConnectionServiceProvider($this->app);
        static::assertSame(['db_connection'], $provider->provides());
    }

    /**
     * @test
     */
    public function it_registers_db_connection()
    {
        $this->refreshApplication();
        $provider = new DbConnectionServiceProvider($this->app);
        $provider->register();
        static::assertInstanceOf(Connection::class, $this->app->make('db_connection'));
    }
}
