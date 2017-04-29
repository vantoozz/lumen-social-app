<?php declare(strict_types = 1);

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
        $availableTypes = [
            self::SYNC,
            self::LOGIN
        ];

        if (!in_array($type, $availableTypes, true)) {
            throw new InvalidArgumentException('No such activity type');
        }
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
}
