<?php declare(strict_types = 1);

namespace App\Repositories\Resources;

use App\Exceptions\NotFoundInRepositoryException;
use App\Exceptions\RepositoryException;
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
     * @throws NotFoundInRepositoryException
     */
    public function getById($id);

    /**
     * @param  ResourceInterface $resource
     *
     * @return ResourceInterface
     *
     * @throws RepositoryException
     */
    public function store(ResourceInterface $resource);
}
