<?php

namespace App;

use Illuminate\Contracts\Support\Arrayable;

/**
 * Interface ModelInterface
 * @package App
 */
interface ModelInterface extends Arrayable
{

    const FORMAT_DATETIME = 'Y-m-d H:i:s';
    const FORMAT_DATE = 'Y-m-d';

    const FIELD_ID = 'id';
    const FIELD_CREATED_AT = 'created_at';
    const FIELD_UPDATED_AT = 'updated_at';

    /**
     * @return int
     */
    public function getId();

    /**
     * @param $id
     * @return ModelInterface
     */
    public function setId($id);

    /**
     * @return ModelInterface
     */
    public function touch();

    /**
     * @param  array $data
     * @return ModelInterface
     */
    public function fill(array $data = []);
}
