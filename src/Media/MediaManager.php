<?php declare(strict_types = 1);

namespace App\Media;

use App\Exceptions\DownloaderException;
use App\Media\Cdn\CdnInterface;
use App\Media\Downloader\DownloaderInterface;

/**
 * Class MediaManager
 * @package App\Media
 */
class MediaManager
{

    /**
     * @var CdnInterface
     */
    private $cdn;

    /**
     * @var DownloaderInterface
     */
    private $downloader;

    /**
     * MediaManager constructor.
     *
     * @param DownloaderInterface $downloader
     * @param CdnInterface        $cdn
     */
    public function __construct(DownloaderInterface $downloader, CdnInterface $cdn)
    {
        $this->cdn = $cdn;
        $this->downloader = $downloader;
    }

    /**
     * @param $url
     *
     * @throws DownloaderException
     */
    public function uploadFromUrl($url)
    {
        $path = $this->makePath($url);
        $data = $this->downloader->download($url);
        $this->cdn->upload($path, $data);
    }

    /**
     * @param  string $filename
     *
     * @return string
     */
    public function makePath($filename)
    {
        $extension = pathinfo($filename)['extension'];
        $path = preg_replace('/^(..)(..)(.*)$/u', '$1/$2/$3', sha1($filename)) . '.' . $extension;

        return $path;
    }
}
