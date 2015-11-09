<?php

namespace App\Cdn;

use App\TestCase;
use Illuminate\Filesystem\FilesystemManager;

class FilesystemCdnTest extends TestCase
{
    /**
     * @test
     */
    public function it_makes_path()
    {
        $filesystem = static::getMockBuilder(FilesystemManager::class)->disableOriginalConstructor()->getMock();
        /** @var FilesystemManager $filesystem */
        $cdn = new FilesystemCdn($filesystem);
        static::assertSame('media/e9/0b/199893d9a50e522697bb4ea4b5ffbce5629b.jpg', $cdn->makePath('aaa.jpg'));
    }

}
