<?php declare(strict_types = 1);

namespace App\Providers;

use App\TestCase;
use Whoops\Run;

class WhoopsServiceProviderTest extends TestCase
{
    /**
     * @test
     */
    public function it_provides_whoops()
    {
        $this->refreshApplication();
        $provider = new WhoopsServiceProvider($this->app);
        static::assertSame([Run::class], $provider->provides());
    }

    /**
     * @test
     */
    public function it_registers_users_repository()
    {
        $this->refreshApplication();
        $provider = new WhoopsServiceProvider($this->app);
        $provider->register();
        static::assertInstanceOf(Run::class, $this->app->make(Run::class));
    }
}
