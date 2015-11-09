<?php

namespace App\Media\Cdn;

/**
 * Interface CdnInterface
 * @package App\Media\Cdn
 */
interface CdnInterface
{
    /**
     * @param string $path
     * @param string $data
     */
    public function upload($path, $data);
}
