<?php

namespace App\Media\Cdn;

use App\TestCase;
use Illuminate\Filesystem\FilesystemManager;

class FilesystemCdnTest extends TestCase
{
    /**
     * @test
     */
    public function it_uploads_to_cdn()
    {
        $storage = $this->getMockBuilder(FilesystemManager::class)
            ->disableOriginalConstructor()
            ->setMethods(['put'])
            ->getMock();

        $storage
            ->expects(static::once())
            ->method('put')
            ->with('media/photo.jpg', 'some data');

        /** @var FilesystemManager $storage */
        $cdn = new FilesystemCdn($storage);

        $cdn->upload('photo.jpg', 'some data');
    }
}
