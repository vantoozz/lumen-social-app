<?php

namespace App;

/**
 * Class CDN
 * @package App
 */
class CDN
{

    const PREFIX_MEDIA = 'media';

    /**
     * @param $url
     */
    public function uploadFromUrl($url)
    {
        $path = $this->makePath($url);
        $contents = file_get_contents($url);
        /** @var \Illuminate\Contracts\Filesystem\Filesystem $storage */
        $storage = app('filesystem');
        $storage->put($path, $contents);
    }

    /**
     * @param  string $name
     * @return string
     */
    public function makePath($name)
    {
        $extension = pathinfo($name)['extension'];
        $path = sha1($name);

        $path_part_3 = substr($path, 0, 2);
        $path_part_2 = substr($path, 2, 2);
        $path_part_1 = substr($path, 4);
        $path = self::PREFIX_MEDIA . '/' . $path_part_3 . '/' . $path_part_2 . '/' . $path_part_1 . '.' . $extension;

        return $path;
    }
}
