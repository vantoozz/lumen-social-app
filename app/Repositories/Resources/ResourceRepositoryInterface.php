<?php

namespace App\Repositories\Resources;

use App\Repositories\RepositoryInterface;
use App\Resources\ResourceInterface;


/**
 * Interface ResourceRepositoryInterface
 * @package App\Repositories\Resources
 */
interface ResourceRepositoryInterface extends RepositoryInterface
{
    /**
     * @param  int $id
     * @return ResourceInterface
     */
    public function getById($id);

    /**
     * @param  ResourceInterface $resource
     * @return ResourceInterface
     */
    public function store(ResourceInterface $resource);
}