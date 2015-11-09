<?php

namespace App\Media;

use App\Media\Cdn\CdnInterface;
use App\Media\Downloader\DownloaderInterface;
use App\TestCase;

class MediaManagerTest extends TestCase
{
    /**
     * @test
     */
    public function it_makes_path()
    {
        /** @var DownloaderInterface $downloader */
        $downloader = static::getMock(DownloaderInterface::class);
        /** @var CdnInterface $cdn */
        $cdn = static::getMock(CdnInterface::class);
        $manager = new MediaManager($downloader, $cdn);
        static::assertSame('e9/0b/199893d9a50e522697bb4ea4b5ffbce5629b.jpg', $manager->makePath('aaa.jpg'));
    }

    /**
     * @test
     */
    public function it_uploads_from_url()
    {
        $downloader = static::getMock(DownloaderInterface::class);
        $downloader
            ->expects(static::once())
            ->method('download')
            ->with('aaa.jpg')
            ->willReturn('some date');

        $cdn = static::getMock(CdnInterface::class);
        $cdn
            ->expects(static::once())
            ->method('upload')
            ->with('e9/0b/199893d9a50e522697bb4ea4b5ffbce5629b.jpg', 'some date')
            ->willReturn(null);

        /** @var DownloaderInterface $downloader */
        /** @var CdnInterface $cdn */
        $manager = new MediaManager($downloader, $cdn);
        $manager->uploadFromUrl('aaa.jpg');
    }

}
