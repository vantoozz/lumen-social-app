<?php

namespace App\Jobs;

use App\User;
use DateTime;
use Illuminate\Contracts\Bus\SelfHandling;
use Laravel\Lumen\Routing\DispatchesCommands;

/**
 * Class SyncUserDataIfNeeded
 * @package App\Jobs
 */
class SyncUserDataIfNeeded implements SelfHandling
{

    use DispatchesCommands;

    /**
     * @var User
     */
    private $user;

    /**
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     *
     */
    public function handle()
    {
        $now = new DateTime;

    }
}
