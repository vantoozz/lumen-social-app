<?php

namespace App\Providers;

use App\Social\Provider\SocialProviderLocator;
use Illuminate\Support\ServiceProvider;

/**
 * Class SocialProviderLocatorServiceProvider
 * @package App\Providers
 */
class SocialProviderLocatorServiceProvider extends ServiceProvider
{

    /**
     * @var bool
     */
    protected $defer = false;

    /**
     * @return array
     */
    public function provides()
    {
        return [SocialProviderLocator::class];
    }

    /**
     * Register any application services.
     * @return void
     */
    public function register()
    {
        $this->app->singleton(SocialProviderLocator::class, function () {
            return new SocialProviderLocator($this->app);
        });
    }
}
