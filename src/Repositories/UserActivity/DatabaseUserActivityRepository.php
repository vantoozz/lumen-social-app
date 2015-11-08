<?php

namespace App\Repositories\UserActivity;

use App\Activities\ActivityType;
use App\Activities\UserActivity;
use App\Exceptions\InvalidArgumentException;
use App\Repositories\Resources\DatabaseResourceRepository;
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
     * @param UserActivity $activity
     */
    public function store(UserActivity $activity)
    {
        $field = $this->makeFieldName($activity->getType());

        $this->connection->update(
            'UPDATE `users` SET `' . $field . '` = ? WHERE `id`=?',
            [$activity->getDatetime()->format(DatabaseResourceRepository::FORMAT_DATETIME), $activity->getUserId()]
        );
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
