<?php

namespace App\Media\Downloader;

use App\Exceptions\DownloaderException;

/**
 * Interface DownloaderInterface
 * @package App\Media\Downloader
 */
interface DownloaderInterface
{
    /**
     * @param $url
     * @return string
     * @throws DownloaderException
     */
    public function download($url);
}