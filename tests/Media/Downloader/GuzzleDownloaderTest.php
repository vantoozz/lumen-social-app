<?php

namespace App\Media\Downloader;

use App\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\StreamInterface;

class GuzzleDownloaderTest extends TestCase
{

    /**
     * @test
     */
    public function it_downloads_a_file()
    {
        $client = $this->getMockBuilder(Client::class)
            ->setMethods(['get'])
            ->getMock();

        $response = new Response(200, [], 'some data');
        $client
            ->expects(static::once())
            ->method('get')
            ->with('http://example.com/photo.jpg')
            ->willReturn($response);

        /** @var Client $client */
        $downloader = new GuzzleDownloader($client);
        $data = $downloader->download('http://example.com/photo.jpg');

        static::assertSame('some data', $data);
    }

    /**
     * @test
     * @expectedException     \App\Exceptions\DownloaderException
     * @expectedExceptionMessage Cannot download file: http://example.com/photo.jpg
     */
    public function it_throws_exception_unless_response_status_is_code_200()
    {
        $client = $this->getMockBuilder(Client::class)
            ->setMethods(['get'])
            ->getMock();

        $response = new Response(400, [], 'some data');
        $client
            ->expects(static::once())
            ->method('get')
            ->with('http://example.com/photo.jpg')
            ->willReturn($response);

        /** @var Client $client */
        $downloader = new GuzzleDownloader($client);
        $downloader->download('http://example.com/photo.jpg');
    }


    /**
     * @test
     * @expectedException     \App\Exceptions\DownloaderException
     * @expectedExceptionMessage some error
     * @expectedExceptionCode 1234
     */
    public function it_catches_guzzle_exceptions()
    {
        $client = $this->getMockBuilder(Client::class)
            ->setMethods(['get'])
            ->getMock();
        $response = $this->createMock(Response::class);
        $body = $this->createMock(StreamInterface::class);

        $response
            ->expects(static::once())
            ->method('getStatusCode')
            ->willReturn(200);

        $response
            ->expects(static::once())
            ->method('getBody')
            ->willReturn($body);

        $body
            ->expects(static::once())
            ->method('getContents')
            ->willThrowException(new \RuntimeException('some error', 1234));

        $client
            ->expects(static::once())
            ->method('get')
            ->with('http://example.com/photo.jpg')
            ->willReturn($response);

        /** @var Client $client */
        $downloader = new GuzzleDownloader($client);
        $downloader->download('http://example.com/photo.jpg');
    }
}
