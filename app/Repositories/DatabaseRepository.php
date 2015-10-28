<?php

namespace App\Repositories;

use App\Exceptions\NotFoundInRepositoryException;
use App\Exceptions\RepositoryException;
use App\Resources\ResourceInterface;
use Exception;
use Illuminate\Database\Connection;
use Throwable;

/**
 * Class DatabaseRepository
 * @package App\Repositories
 */
abstract class DatabaseRepository extends AbstractRepository
{

    /**
     * @var Connection
     */
    protected $db;

    /**
     * @var string
     */
    protected static $table = '';

    /**
     * @param Connection $db
     */
    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    /**
     * @param  int $id
     *
     * @return ResourceInterface
     * @throws NotFoundInRepositoryException
     */
    public function getById($id)
    {
        $results = $this->db->select(
            'SELECT * FROM `' . static::$table . '` WHERE `id` = :id LIMIT 1',
            ['id' => $id]
        );
        if (0 === count($results)) {
            throw new NotFoundInRepositoryException('Not found');
        }

        return $this->makeModel((array)$results[0]);
    }

    /**
     * @param  ResourceInterface $model
     *
     * @return ResourceInterface
     *
     * @throws RepositoryException
     */
    public function store(ResourceInterface $model)
    {
        $action = $model->getId() ? 'update' : 'create';
        try {
            return $this->$action($model);
        } catch (Throwable $throwable) {
            throw new RepositoryException($throwable->getMessage(), $throwable->getCode(), $throwable);
        } catch (Exception $exception) {
            throw new RepositoryException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    /**
     * @param  ResourceInterface $model
     *
     * @return ResourceInterface
     *
     * @throws \Exception
     * @throws \Throwable
     */
    protected function create(ResourceInterface $model)
    {
        list($data, $keys) = $this->prepareModel($model);

        $id = $this->db->transaction(
            function () use ($data, $keys) {
                $this->db->insert(
                    'INSERT INTO `' . static::$table . '` (' . implode(', ', array_keys($data)) . ')
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
     * @param  ResourceInterface $model
     *
     * @return array
     */
    private function prepareModel(ResourceInterface $model)
    {
        $model->touch();
        $data = $model->toArray();
        $keys = $this->prepareKeys($data);

        return array($data, $keys);
    }

    /**
     * @param $data
     *
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
     * @param  ResourceInterface $model
     *
     * @return ResourceInterface
     */
    protected function update(ResourceInterface $model)
    {
        list($data) = $this->prepareModel($model);

        $statements = [];
        $keys = array_keys($data);
        foreach ($keys as $key) {
            $statements[] = $key . ' = ?';
        }

        $this->db->update(
            'UPDATE `' . static::$table . '` SET ' . implode(', ', $statements) . ' WHERE `id`=?',
            array_merge(array_values($data), [$data[ResourceInterface::FIELD_ID]])
        );

        return $model;
    }

}
