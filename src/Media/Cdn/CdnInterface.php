<?php declare(strict_types = 1);

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
