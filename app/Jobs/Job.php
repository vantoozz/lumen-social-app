<?php namespace App\Jobs;

use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Class Job
 * @package App\Jobs
 */
abstract class Job implements SelfHandling, ShouldBeQueued
{
    use InteractsWithQueue, SerializesModels;
}
