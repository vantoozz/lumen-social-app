<?php

namespace App\Social\Provider;

use App\TestCase;
use Illuminate\Container\Container;

class SocialProviderLocatorTest extends TestCase
{
    /**
     * @test
     */
    public function it_builds_provider()
    {
        $container = new Container;
        $container->singleton('social.provider', function () {
            return static::getMock(SocialProviderInterface::class);
        });
        $locator = new SocialProviderLocator($container);

        static::assertInstanceOf(SocialProviderInterface::class, $locator->build('provider'));
    }
}
