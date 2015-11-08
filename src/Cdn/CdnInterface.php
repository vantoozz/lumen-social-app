<?php

namespace App\Cdn;


/**
 * Interface CdnInterface
 * @package App\Cdn
 */
interface CdnInterface
{
    /**
     * @param $url
     */
    public function uploadFromUrl($url);

    /**
     * @param  string $filename
     * @return string
     */
    public function makePath($filename);
}