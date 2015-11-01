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
    protected $db;

    /**
     * @param Connection $db
     */
    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    /**
     * @param UserActivity $activity
     */
    public function store(UserActivity $activity)
    {
        $field = $this->makeFieldName($activity->getType());

        $this->db->update(
            'UPDATE `users` SET `' . $field . '` = ? WHERE `id`=?',
            [$activity->getDatetime()->format(DatabaseResourceRepository::FORMAT_DATETIME), $activity->getUserId()]
        );
    }

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