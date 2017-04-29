<?php declare(strict_types = 1);

namespace App\Media\Cdn;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemManager;

/**
 * Class FilesystemCdn
 * @package App\Media\Cdn
 */
class FilesystemCdn implements CdnInterface
{

    const PREFIX_MEDIA = 'media';

    /**
     * @var Filesystem
     */
    private $storage;

    /**
     * @param FilesystemManager $storage
     */
    public function __construct(FilesystemManager $storage)
    {
        /** @var Filesystem $storage */
        $this->storage = $storage;
    }

    /**
     * @param string $path
     * @param string $data
     */
    public function upload($path, $data)
    {
        $this->storage->put(self::PREFIX_MEDIA . '/' . $path, $data);
    }
}
