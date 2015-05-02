<?php

namespace App\Repositories;


use App\Exceptions\NotFoundInRepositoryException;
use App\ModelInterface;

abstract class AbstractRepository implements RepositoryInterface
{
    /**
     * @param array $data
     * @throws NotFoundInRepositoryException
     * @return ModelInterface
     */
    abstract protected function makeModel(array $data);
}