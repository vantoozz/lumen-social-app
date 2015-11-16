<?php

namespace App\Media\Downloader;

use App\Exceptions\DownloaderException;
use GuzzleHttp\Client;

/**
 * Class GuzzleDownloader
 * @package App\Media\Downloader
 */
class GuzzleDownloader implements DownloaderInterface
{

    /**
     * @var Client
     */
    private $client;

    /**
     * GuzzleDownloader constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param $url
     * @return string
     * @throws DownloaderException
     */
    public function download($url)
    {
        $response = $this->client->get($url);
        if (200 !== $response->getStatusCode()) {
            throw new DownloaderException('Cannot download file: ' . $url);
        }
        try {
            return $response->getBody()->getContents();
        } catch (\RuntimeException $e) {
            throw new DownloaderException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
