<?php

namespace App\Hydrators;

use App\Exceptions\HydratorException;
use Carbon\Carbon;

/**
 * Class AbstractHydrator
 * @package App\Hydrators
 */
abstract class AbstractHydrator implements HydratorInterface
{
    /**
     * @param $field
     * @param array $data
     * @return array
     * @throws HydratorException
     */
    protected function hydrateDate($field, array $data)
    {
        if (!array_key_exists($field, $data)) {
            return $data;
        }

        try {
            $data[$field] = (new Carbon)->createFromFormat(self::FORMAT_DATE, $data[$field]);
        } catch (\InvalidArgumentException $e) {
            throw new HydratorException($e->getMessage(), $e->getCode(), $e);
        }

        return $data;
    }

    /**
     * @param $date
     * @return null|string
     */
    protected function extractNullableDate($date)
    {
        if (!$date instanceof Carbon) {
            return null;
        }

        return $date->format(self::FORMAT_DATE);
    }

    /**
     * @param $field
     * @param array $data
     * @return array
     */
    protected function hydrateEmptyStringAsNull($field, array $data)
    {
        if (!array_key_exists($field, $data)) {
            return $data;
        }

        if ('' === $data[$field]) {
            $data[$field] = null;
        }

        return $data;
    }
}
