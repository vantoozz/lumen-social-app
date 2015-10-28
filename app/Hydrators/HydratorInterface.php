<?php

namespace App\Hydrators;

use App\Resources\ResourceInterface;

/**
 * Interface HydratorInterface
 * @package App\Hydrators
 */
interface HydratorInterface
{
    /**
     * @return array
     */
    public function extract();

    /**
     * @param array $data
     * @return ResourceInterface
     */
    public function hydrate(array $data);
}