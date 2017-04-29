<?php declare(strict_types = 1);

namespace App\Providers;

use App\Media\Cdn\CdnInterface;
use App\Media\Cdn\FilesystemCdn;
use App\Media\Downloader\DownloaderInterface;
use App\Media\Downloader\GuzzleDownloader;
use App\Media\MediaManager;
use Guzzle\Common\Exception\RuntimeException;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

/**
 * Class MediaManagerServiceProvider
 * @package App\Providers
 */
class MediaManagerServiceProvider extends ServiceProvider
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
        return [
            MediaManager::class,
            CdnInterface::class,
            DownloaderInterface::class
        ];
    }

    /**
     * Register any application services.
     *
     * @return void
     *
     * @throws RuntimeException
     */
    public function register()
    {
        $this->app->singleton(CdnInterface::class, function () {
            /** @var \Illuminate\Filesystem\FilesystemManager $storage */
            $storage = $this->app->make('filesystem');

            return new FilesystemCdn($storage);
        });

        $this->app->singleton(DownloaderInterface::class, function () {
            return new GuzzleDownloader(new Client);
        });

        $this->app->singleton(MediaManager::class, function () {
            /** @var DownloaderInterface $downloader */
            $downloader = $this->app->make(DownloaderInterface::class);
            /** @var CdnInterface $cdn */
            $cdn = $this->app->make(CdnInterface::class);

            return new MediaManager($downloader, $cdn);
        });
    }
}
