<?php

namespace App\Repositories\UserActivity;

use App\Activities\ActivityType;
use App\Activities\UserActivity;
use App\Exceptions\InvalidArgumentException;
use App\Exceptions\NotFoundInRepositoryException;
use App\Exceptions\RepositoryException;
use App\Repositories\Resources\DatabaseResourceRepository;
use Carbon\Carbon;
use Illuminate\Database\Connection;

/**
 * Class DatabaseUserActivityRepository
 * @package App\Repositories\UserActivity
 */
class DatabaseUserActivityRepository implements UserActivityRepositoryInterface
{

    const FIELD_LAST_LOGIN_AT = 'last_login_at';
    const FIELD_LAST_SYNC_AT = 'last_sync_at';

    /**
     * @var string
     */
    protected static $table = 'users';

    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param ActivityType $activityType
     * @param $userId
     * @throws InvalidArgumentException
     * @throws NotFoundInRepositoryException
     * @throws \InvalidArgumentException
     * @return UserActivity
     */
    public function getActivity(ActivityType $activityType, $userId)
    {
        $field = $this->makeFieldName($activityType);

        $date = $this->connection
            ->table(static::$table)
            ->where('id', $userId)
            ->value($field);

        if (null === $date) {
            throw new NotFoundInRepositoryException('No activity found');
        }

        $activity = new UserActivity;
        $activity->setType($activityType);
        $activity->setUserId($userId);
        $activity->setDatetime(new Carbon($date));

        return $activity;
    }


    /**
     * @param UserActivity $activity
     * @throws InvalidArgumentException
     * @throws RepositoryException
     */
    public function store(UserActivity $activity)
    {
        if (!$activity->getType() instanceof ActivityType) {
            throw new InvalidArgumentException('Activity type not set');
        }

        $field = $this->makeFieldName($activity->getType());

        $datetime = clone $activity->getDatetime();
        $datetime->setTimezone(new \DateTimeZone('UTC'));
        $datetime = $datetime->format(DatabaseResourceRepository::FORMAT_DATETIME);

        try {
            $this->connection
                ->table(static::$table)
                ->where('id', $activity->getUserId())
                ->update([$field => $datetime]);
        } catch (\InvalidArgumentException $e) {
            throw new RepositoryException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param ActivityType $type
     * @return string
     * @throws InvalidArgumentException
     */
    private function makeFieldName(ActivityType $type)
    {
        switch ($type->getType()) {
            case ActivityType::LOGIN:
                return self::FIELD_LAST_LOGIN_AT;
                break;
            case ActivityType::SYNC:
                return self::FIELD_LAST_SYNC_AT;
                break;
            default:
                throw new InvalidArgumentException('No field for this activity type');
        }
    }
}
