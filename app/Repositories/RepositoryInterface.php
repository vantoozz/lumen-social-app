<?php

namespace App\Repositories;

use App\ModelInterface;

/**
 * Interface RepositoryInterface
 * @package App\Repositories
 */
interface RepositoryInterface
{
    /**
     * @param int $id
     * @return ModelInterface
     */
    public function getById($id);

    /**
     * @param ModelInterface $model
     * @return ModelInterface
     */
    public function save(ModelInterface $model);
    /**
     * @param ModelInterface $model
     * @return ModelInterface
     */
    public function create(ModelInterface $model);
    /**
     * @param ModelInterface $model
     * @return ModelInterface
     */
    public function update(ModelInterface $model);
}