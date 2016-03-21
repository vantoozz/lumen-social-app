<?php

namespace App\Repositories\Resources;

use App\Exceptions\NotFoundInRepositoryException;
use App\Exceptions\RepositoryException;
use App\Hydrators\HydratorInterface;
use App\Resources\ResourceInterface;
use Carbon\Carbon;
use Illuminate\Database\Connection;

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
    protected $connection;

    /**
     * @var HydratorInterface
     */
    protected $hydrator;

    /**
     * @var string
     */
    protected static $table = '';

    /**
     * @param Connection $connection
     * @param HydratorInterface $hydrator
     */
    public function __construct(Connection $connection, HydratorInterface $hydrator)
    {
        $this->connection = $connection;
        $this->hydrator = $hydrator;
    }

    /**
     * @param  int $id
     * @return ResourceInterface
     * @throws NotFoundInRepositoryException
     */
    public function getById($id)
    {
        $data = $this->connection->table(static::$table)->find($id);

        if (null === $data) {
            throw new NotFoundInRepositoryException('Not found');
        }

        return $this->hydrator->hydrate((array)$data);
    }

    /**
     * @param  ResourceInterface $resource
     * @return ResourceInterface
     * @throws RepositoryException
     */
    public function store(ResourceInterface $resource)
    {
        if ($resource->getId()) {
            return $this->update($resource);
        }

        return $this->create($resource);
    }

    /**
     * @param  ResourceInterface $resource
     * @return ResourceInterface
     */
    protected function create(ResourceInterface $resource)
    {
        $data = $this->extractResource($resource);
        $data[self::FIELD_CREATED_AT] = $data[self::FIELD_UPDATED_AT];

        $id = $this->connection->table(static::$table)->insertGetId($data);

        $resource->setId($id);

        return $resource;
    }


    /**
     * @param  ResourceInterface $resource
     * @return ResourceInterface
     * @throws RepositoryException
     */
    protected function update(ResourceInterface $resource)
    {
        $data = $this->extractResource($resource);

        try {
            $this->connection->table(static::$table)->where('id', $resource->getId())->update($data);
        } catch (\InvalidArgumentException $e) {
            throw new RepositoryException($e->getMessage(), $e->getCode(), $e);
        }

        return $resource;
    }

    /**
     * @param ResourceInterface $resource
     * @return array
     */
    protected function extractResource(ResourceInterface $resource)
    {
        $data = $this->hydrator->extract($resource);

        $datetime = (new Carbon)->setTimezone(new \DateTimeZone('UTC'))->format(self::FORMAT_DATETIME);
        $data[self::FIELD_UPDATED_AT] = $datetime;

        return $data;
    }
}
