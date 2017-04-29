<?php declare(strict_types = 1);

namespace App\Providers;

use App\Social\Provider\VK;
use App\TestCase;

class VKServiceProviderTest extends TestCase
{
    /**
     * @test
     */
    public function it_provides_vk()
    {
        $this->refreshApplication();
        $provider = new VKServiceProvider($this->app);
        static::assertSame(['social.vk'], $provider->provides());
    }

    /**
     * @test
     */
    public function it_registers_db_connection()
    {
        $this->refreshApplication();
        $provider = new VKServiceProvider($this->app);
        $provider->register();
        static::assertInstanceOf(VK::class, $this->app->make('social.vk'));
    }
}
