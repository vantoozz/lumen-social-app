<?php

namespace App\Providers;

use App\Media\Cdn\CdnInterface;
use App\Media\Downloader\DownloaderInterface;
use App\Media\MediaManager;
use App\TestCase;

class MediaManagerServiceProviderTest extends TestCase
{
    /**
     * @test
     */
    public function it_provides_media()
    {
        $this->refreshApplication();
        $provider = new MediaManagerServiceProvider($this->app);
        static::assertSame([
            MediaManager::class,
            CdnInterface::class,
            DownloaderInterface::class
        ], $provider->provides());
    }

    /**
     * @test
     */
    public function it_registers_media_manager()
    {
        $this->refreshApplication();
        $provider = new MediaManagerServiceProvider($this->app);
        $provider->register();
        static::assertInstanceOf(MediaManager::class, $this->app->make(MediaManager::class));
    }

    /**
     * @test
     */
    public function it_registers_cdn()
    {
        $this->refreshApplication();
        $provider = new MediaManagerServiceProvider($this->app);
        $provider->register();
        static::assertInstanceOf(CdnInterface::class, $this->app->make(CdnInterface::class));
    }

    /**
     * @test
     */
    public function it_registers_downloader()
    {
        $this->refreshApplication();
        $provider = new MediaManagerServiceProvider($this->app);
        $provider->register();
        static::assertInstanceOf(DownloaderInterface::class, $this->app->make(DownloaderInterface::class));
    }
}
