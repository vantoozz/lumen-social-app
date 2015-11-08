<?php

namespace App\Miners;

use Carbon\Carbon;

/**
 * Class UserActivityMiner
 * @package App\Miners
 */
class UserActivityMiner
{

    /**
     * @return Carbon
     */
    public function getLastLoginDate()
    {
        return new Carbon;
    }

    /**
     * @return Carbon
     */
    public function getLastSyncDate()
    {
        return new Carbon;
    }
}
