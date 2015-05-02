<?php

namespace App;

use DateTime;

/**
 * Class AbstractModel
 * @package App
 */
class AbstractModel implements ModelInterface
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
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
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
     * @return ModelInterface
     */
    public function setId($id)
    {
        $this->id = (int)$id;

        return $this;
    }

    /**
     * @return ModelInterface
     */
    public function touch()
    {
        $now = new DateTime();
        if (empty($this->created_at)) {
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


    protected function formatDateTime($datetime){
        if(empty($datetime)){
            return null;
        }
        if(!$datetime instanceof DateTime){
            return $datetime;
        }
        return $datetime->format(self::DATE_FORMAT);
    }
}
