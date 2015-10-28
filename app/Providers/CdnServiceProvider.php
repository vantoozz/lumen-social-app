<?php namespace App\Providers;

use App\CDN;
use Illuminate\Support\ServiceProvider;

class CdnServiceProvider extends ServiceProvider
{

    /**
     * @var bool
     */
    protected $defer = true;

    /**
     * @return array
     */
    public function provides()
    {
        return [CDN::class];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            CDN::class,
            function () {
                /** @var \Illuminate\Filesystem\FilesystemManager $storage */
                $storage = $this->app->make('filesystem');

                return new CDN($storage);
            }
        );
    }
}
