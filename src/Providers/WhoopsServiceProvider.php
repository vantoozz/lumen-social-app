<?php

namespace App\Providers;


use Illuminate\Support\ServiceProvider;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

class WhoopsServiceProvider extends ServiceProvider
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
        return [Run::class];
    }

    /**
     * Register the service provider.
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     */
    public function register()
    {
        $this->app->bind(Run::class, function () {
            $whoops = new Run;
            $whoops->pushHandler(new PrettyPageHandler);

            return $whoops;
        });
    }
}