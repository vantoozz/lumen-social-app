<?php

namespace App\Repositories;

use App\Exceptions\NotFoundInRepositoryException;
use App\ModelInterface;
use Illuminate\Database\Connection;

/**
 * Class DatabaseRepository
 * @package App\Repositories
 */
abstract class DatabaseRepository extends AbstractRepository
{

    /** @var Connection */
    protected $db;

    /**
     * @var string
     */
    protected $table = '';

    /**
     *
     */
    public function __construct()
    {
        $this->db = app()->make('db')->connection();
    }

    /**
     * @param  int $id
     * @return ModelInterface
     * @throws NotFoundInRepositoryException
     */
    public function getById($id)
    {
        $results = $this->db->select(
            'SELECT * FROM `' . $this->table . '` WHERE `id` = :id LIMIT 1',
            ['id' => $id]
        );
        if (empty($results)) {
            throw new NotFoundInRepositoryException('Not found');
        }

        return $this->makeModel((array)$results[0]);
    }

    /**
     * @param  ModelInterface $model
     * @return ModelInterface
     */
    public function save(ModelInterface $model)
    {
        if (empty($model->getId())) {
            return $this->create($model);
        }

        return $this->update($model);
    }

    /**
     * @param  ModelInterface $model
     * @return ModelInterface
     */
    public function create(ModelInterface $model)
    {
        list($data, $keys) = $this->prepareModel($model);

        $id = $this->db->transaction(
            function () use ($data, $keys) {
                $this->db->insert(
                    'INSERT INTO ' . $this->table . ' (' . implode(', ', array_keys($data)) . ')
                    VALUES (' . implode(', ', $keys) . ')'
                    ,
                    $data
                );

                return $this->db->getPdo()->lastInsertId();
            }
        );

        $model->setId($id);

        return $model;
    }

    /**
     * @param  ModelInterface $model
     * @return array
     */
    private function prepareModel(ModelInterface $model)
    {
        $model->touch();
        $data = $model->toArray();
        $keys = $this->prepareKeys($data);

        return array($data, $keys);
    }

    /**
     * @param $data
     * @return array
     */
    private function prepareKeys($data)
    {
        $keys = array_map(
            function ($key) {
                return ':' . $key;
            },
            array_keys($data)
        );

        return $keys;
    }

    /**
     * @param  ModelInterface $model
     * @return ModelInterface
     */
    public function update(ModelInterface $model)
    {
        list($data) = $this->prepareModel($model);

        $statements = [];
        foreach (array_keys($data) as $key) {
            $statements[] = $key . ' = ?';
        }

        $this->db->update(
            'UPDATE ' . $this->table . ' SET ' . implode(', ', $statements) . ' WHERE `id`=?'
            ,
            array_merge(array_values($data), [$data[ModelInterface::FIELD_ID]])
        );

        return $model;
    }

}
