<?php namespace App\Providers;

use App\Social\Provider\VK;
use Illuminate\Support\ServiceProvider;

class VKServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            'social.vk',
            function () {
                return new VK(new \Novanova\VK\VK(getenv('VK_APP_ID'), getenv('VK_SECRET')));
            }
        );
    }
}
