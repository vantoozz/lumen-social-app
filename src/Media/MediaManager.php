<?php

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
     * @param DownloaderInterface $downloader
     * @param CdnInterface $cdn
     */
    public function __construct(DownloaderInterface $downloader, CdnInterface $cdn)
    {
        $this->cdn = $cdn;
        $this->downloader = $downloader;
    }

    /**
     * @param $url
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
     * @return string
     */
    public function makePath($filename)
    {
        $extension = pathinfo($filename)['extension'];
        $path = sha1($filename);

        $part1 = substr($path, 0, 2);
        $part2 = substr($path, 2, 2);
        $part3 = substr($path, 4);
        $path = $part1 . '/' . $part2 . '/' . $part3 . '.' . $extension;

        return $path;
    }
}