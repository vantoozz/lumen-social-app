<?php

namespace App\Repositories\Resources;

use App\Exceptions\NotFoundInRepositoryException;
use App\Exceptions\RepositoryException;
use App\Hydrators\HydratorInterface;
use App\Resources\ResourceInterface;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Connection;
use Throwable;


/**
 * Class DatabaseResourceRepository
 * @package App\Repositories\Resources
 */
abstract class DatabaseResourceRepository extends AbstractResourceRepository
{

    const FIELD_CREATED_AT = 'created_at';
    const FIELD_UPDATED_AT = 'updated_at';

    const FORMAT_DATETIME = 'Y-m-d H:i:s';

    /**
     * @var Connection
     */
    protected $db;

    /**
     * @var HydratorInterface
     */
    protected $hydrator;

    /**
     * @var string
     */
    protected static $table = '';

    /**
     * @param Connection $db
     * @param HydratorInterface $hydrator
     */
    public function __construct(Connection $db, HydratorInterface $hydrator)
    {
        $this->db = $db;
        $this->hydrator = $hydrator;
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

        return $this->hydrator->hydrate((array)$results[0]);
    }

    /**
     * @param  ResourceInterface $resource
     *
     * @return ResourceInterface
     *
     * @throws RepositoryException
     */
    public function store(ResourceInterface $resource)
    {
        $action = $resource->getId() ? 'update' : 'create';
        try {
            return $this->$action($resource);
        } catch (Throwable $throwable) {
            throw new RepositoryException($throwable->getMessage(), $throwable->getCode(), $throwable);
        } catch (Exception $exception) {
            throw new RepositoryException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    /**
     * @param  ResourceInterface $resource
     *
     * @return ResourceInterface
     *
     * @throws \Exception
     * @throws \Throwable
     */
    protected function create(ResourceInterface $resource)
    {
        list($data, $keys) = $this->prepareResource($resource);

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

        $resource->setId($id);

        return $resource;
    }

    /**
     * @param  ResourceInterface $resource
     *
     * @return array
     */
    private function prepareResource(ResourceInterface $resource)
    {
        $data = $this->hydrator->extract($resource);

        $now = (new Carbon)->format(self::FORMAT_DATETIME);
        $data[self::FIELD_CREATED_AT] = $now;
        $data[self::FIELD_UPDATED_AT] = $now;

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
     * @param  ResourceInterface $resource
     *
     * @return ResourceInterface
     */
    protected function update(ResourceInterface $resource)
    {
        list($data) = $this->prepareResource($resource);
        unset($data[self::FIELD_CREATED_AT]);

        $statements = [];
        $keys = array_keys($data);
        foreach ($keys as $key) {
            $statements[] = $key . ' = ?';
        }

        $this->db->update(
            'UPDATE `' . static::$table . '` SET ' . implode(', ', $statements) . ' WHERE `id`=?',
            array_merge(array_values($data), [$data[ResourceInterface::FIELD_ID]])
        );

        return $resource;
    }

}
