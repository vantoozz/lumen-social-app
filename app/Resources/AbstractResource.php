<?php


namespace App\Resources;

use DateTime;

/**
 * Class AbstractResource
 * @package App\Resources
 */
class AbstractResource implements ResourceInterface
{
    /**
     * @var int
     */
    protected $id;
    /**
     * @var DateTime
     */
    protected $created_at;
    /**
     * @var DateTime
     */
    protected $updated_at;

    /**
     * @var array
     */
    protected $overview = [];

    /**
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->fill($data);
    }

    /**
     * @param  array $data
     *
     * @return ResourceInterface
     */
    public function fill(array $data = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }

        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param  int $id
     *
     * @return ResourceInterface
     */
    public function setId($id)
    {
        $this->id = (int)$id;

        return $this;
    }

    /**
     * @return ResourceInterface
     */
    public function touch()
    {
        $now = new DateTime();
        if (null === $this->created_at) {
            $this->created_at = $now;
        }
        $this->updated_at = $now;

        return $this;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            self::FIELD_ID => $this->id,
            self::FIELD_CREATED_AT => $this->formatDateTime($this->created_at),
            self::FIELD_UPDATED_AT => $this->formatDateTime($this->updated_at),
        ];
    }

    /**
     * @param $datetime
     *
     * @return null|string
     */
    protected function formatDateTime($datetime)
    {
        if (empty($datetime)) {
            return null;
        }
        if (!$datetime instanceof DateTime) {
            return $datetime;
        }

        return $datetime->format(self::FORMAT_DATETIME);
    }

    /**
     * @param $date
     *
     * @return null|string
     */
    protected function formatDate($date)
    {
        if (empty($date)) {
            return null;
        }
        if (!$date instanceof DateTime) {
            return $date;
        }

        return $date->format(self::FORMAT_DATE);
    }
}
