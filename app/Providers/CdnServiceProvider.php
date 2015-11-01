<?php namespace App\Providers;

use App\Cdn\FilesystemCdn;
use Illuminate\Support\ServiceProvider;

/**
 * Class CdnServiceProvider
 * @package App\Providers
 */
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
        return [FilesystemCdn::class];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            FilesystemCdn::class,
            function () {
                /** @var \Illuminate\Filesystem\FilesystemManager $storage */
                $storage = $this->app->make('filesystem');

                return new FilesystemCdn($storage);
            }
        );
    }
}
