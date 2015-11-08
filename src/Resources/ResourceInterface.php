<?php


namespace App\Resources;

/**
 * Interface ResourceInterface
 * @package App\Resources
 */
/**
 * Interface ResourceInterface
 * @package App\Resources
 */
interface ResourceInterface
{

    /**
     *
     */
    const FIELD_ID = 'id';

    /**
     * @return int
     */
    public function getId();

    /**
     * @param $id
     */
    public function setId($id);

    /**
     * @param array $data
     */
    public function populate(array $data);
}
