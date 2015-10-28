<?php

namespace App\Repositories;

use App\Resources\ResourceInterface;

/**
 * Interface RepositoryInterface
 * @package App\Repositories
 */
interface RepositoryInterface
{
    /**
     * @param  int $id
     * @return ResourceInterface
     */
    public function getById($id);

    /**
     * @param  ResourceInterface $model
     * @return ResourceInterface
     */
    public function store(ResourceInterface $model);
}
