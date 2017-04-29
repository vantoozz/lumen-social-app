<?php declare(strict_types = 1);


namespace App\Resources;

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
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param  int $id
     */
    public function setId($id)
    {
        $this->id = (int)$id;
    }

    /**
     * @param array $data
     */
    public function populate(array $data)
    {
        foreach ($data as $field => $value) {
            $method = camel_case('set_' . $field);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }
}
