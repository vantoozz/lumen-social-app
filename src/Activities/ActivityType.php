<?php

namespace App\Activities;

use App\Exceptions\InvalidArgumentException;

/**
 * Class ActivityType
 * @package App\Activities
 */
class ActivityType
{
    const SYNC = 'sync';
    const LOGIN = 'login';

    /**
     * @var array
     */
    private $availableTypes = [
        self::SYNC,
        self::LOGIN
    ];

    /**
     * @var string
     */
    private $type;

    /**
     * ActivityType constructor.
     * @param $type
     * @throws InvalidArgumentException
     */
    public function __construct($type)
    {
        if (!in_array($type, $this->availableTypes, true)) {
            throw new InvalidArgumentException('No such activity type');
        }
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}