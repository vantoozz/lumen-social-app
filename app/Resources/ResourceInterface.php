<?php


namespace App\Resources;

use Illuminate\Contracts\Support\Arrayable;

/**
 * Interface ResourceInterface
 * @package App\Resources
 */
interface ResourceInterface extends Arrayable
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
     * @return ResourceInterface
     */
    public function setId($id);

    /**
     * @return ResourceInterface
     */
    public function touch();

    /**
     * @param  array $data
     * @return ResourceInterface
     */
    public function fill(array $data = []);
}
