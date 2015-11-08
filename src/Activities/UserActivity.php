<?php

namespace App\Activities;

use Carbon\Carbon;

/**
 * Class UserActivity
 * @package App\Activities
 */
class UserActivity
{

    /**
     * @var Carbon
     */
    private $datetime;

    /**
     * @var ActivityType
     */
    private $type;

    /**
     * @var int
     */
    private $userId;

    /**
     * @return Carbon
     */
    public function getDatetime()
    {
        return $this->datetime;
    }

    /**
     * @param Carbon $datetime
     */
    public function setDatetime(Carbon $datetime)
    {
        $this->datetime = $datetime;
    }

    /**
     * @return ActivityType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param ActivityType $type
     */
    public function setType(ActivityType $type)
    {
        $this->type = $type;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId($userId)
    {
        $this->userId = (int)$userId;
    }
}
