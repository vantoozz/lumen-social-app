<?php

namespace App\Providers;

use App\Hydrators\User\VkUserHydrator;
use App\Social\Provider\VK;
use Illuminate\Support\ServiceProvider;

/**
 * Class VKServiceProvider
 * @package App\Providers
 */
class VKServiceProvider extends ServiceProvider
{

    const SERVICE_NAME = 'social.vk';

    /**
     * @var bool
     */
    protected $defer = true;

    /**
     * @return array
     */
    public function provides()
    {
        return [self::SERVICE_NAME];
    }

    /**
     * Register any application services.
     * @return void
     */
    public function register()
    {
        $this->app->singleton(self::SERVICE_NAME, function () {
            return new VK(
                new \Novanova\VK\VK(getenv('VK_APP_ID'), getenv('VK_SECRET')),
                new VkUserHydrator
            );
        });
    }
}
