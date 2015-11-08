<?php

namespace App\Cdn;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemManager;

/**
 * Class FilesystemCdn
 * @package App
 */
class FilesystemCdn extends AbstractCdn
{

    /**
     * @var FilesystemManager
     */
    private $storage;

    /**
     * @param FilesystemManager $storage
     */
    public function __construct(FilesystemManager $storage)
    {
        $this->storage = $storage;
    }

    const PREFIX_MEDIA = 'media';

    /**
     * @param $url
     */
    public function uploadFromUrl($url)
    {
        $path = $this->makePath($url);
        $contents = file_get_contents($url);
        /** @var Filesystem $storage */
        $storage = $this->storage;
        $storage->put($path, $contents);
    }

    /**
     * @param  string $filename
     * @return string
     */
    public function makePath($filename)
    {
        $path = parent::makePath($filename);

        return self::PREFIX_MEDIA . '/' . $path;
    }
}
