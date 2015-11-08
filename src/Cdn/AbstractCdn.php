<?php

namespace App\Cdn;

/**
 * Class AbstractCdn
 * @package App\Cdn
 */
abstract class AbstractCdn implements CdnInterface
{
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
