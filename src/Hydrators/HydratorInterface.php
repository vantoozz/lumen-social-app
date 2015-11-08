<?php

namespace App\Hydrators;

use App\Resources\ResourceInterface;

/**
 * Interface HydratorInterface
 * @package App\Hydrators
 */
interface HydratorInterface
{
    const FORMAT_DATE = 'Y-m-d';

    /**
     * @param ResourceInterface $resource
     * @return array
     */
    public function extract(ResourceInterface $resource);

    /**
     * @param array $data
     * @return ResourceInterface
     */
    public function hydrate(array $data);
}
