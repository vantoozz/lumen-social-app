<?php

namespace App\Repositories\Resources;


use App\Exceptions\NotFoundInRepositoryException;
use App\Exceptions\RepositoryException;
use App\Resources\ResourceInterface;

/**
 * Class DummyResourceRepository
 * @package App\Repositories\Resources
 */
class DummyResourceRepository extends AbstractResourceRepository
{
    /**
     * @param  int $id
     * @return ResourceInterface
     * @throws NotFoundInRepositoryException
     */
    public function getById($id)
    {
        throw new NotFoundInRepositoryException;
    }

    /**
     * @param  ResourceInterface $resource
     * @return ResourceInterface
     * @throws RepositoryException
     */
    public function store(ResourceInterface $resource)
    {
        return $resource;
    }
}