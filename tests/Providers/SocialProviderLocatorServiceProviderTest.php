<?php

namespace App\Providers;

use App\Social\Provider\SocialProviderLocator;
use App\TestCase;

class SocialProviderLocatorServiceProviderTest extends TestCase
{
    /**
     * @test
     */
    public function it_provides_social_provider_locator()
    {
        $this->refreshApplication();
        $provider = new SocialProviderLocatorServiceProvider($this->app);
        static::assertSame([SocialProviderLocator::class], $provider->provides());
    }

    /**
     * @test
     */
    public function it_registers_social_provider_locator()
    {
        $this->refreshApplication();
        $provider = new SocialProviderLocatorServiceProvider($this->app);
        $provider->register();
        static::assertInstanceOf(SocialProviderLocator::class, $this->app->make(SocialProviderLocator::class));
    }
}
