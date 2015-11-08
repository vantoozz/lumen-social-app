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

        $path_part_3 = substr($path, 0, 2);
        $path_part_2 = substr($path, 2, 2);
        $path_part_1 = substr($path, 4);
        $path = $path_part_3 . '/' . $path_part_2 . '/' . $path_part_1 . '.' . $extension;

        return $path;
    }

}