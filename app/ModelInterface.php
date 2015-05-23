<?php

namespace App;


use Illuminate\Contracts\Support\Arrayable;

/**
 * Interface ModelInterface
 * @package App
 */
interface ModelInterface extends Arrayable
{

    const DATE_FORMAT = 'Y-m-d H:i:s';

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
     * @param array $data
     * @return ModelInterface
     */
    public function fill(array $data = []);
}