<?php

namespace App\Social\Provider;

use App\Resources\User;
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

    /**
     * @test
     * @expectedException     \App\Exceptions\FactoryException
     * @expectedExceptionMessage No such social provider: provider
     */
    public function it_throws_exception_if_there_is_no_such_provider_in_the_container()
    {
        $container = new Container;
        $locator = new SocialProviderLocator($container);

        static::assertInstanceOf(SocialProviderInterface::class, $locator->build('provider'));
    }

    /**
     * @test
     * @expectedException     \App\Exceptions\FactoryException
     * @expectedExceptionMessage Not a social provider: App\Resources\User
     */
    public function it_throws_exception_if_built_class_is_not_a_provider()
    {
        $container = new Container;
        $locator = new SocialProviderLocator($container);
        $container->singleton('social.provider', function () {
            return new User;
        });

        static::assertInstanceOf(SocialProviderInterface::class, $locator->build('provider'));
    }
}
