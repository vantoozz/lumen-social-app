<?php

namespace App\Hydrators\User;


use App\Exceptions\HydratorException;
use App\Hydrators\AbstractHydrator;
use App\Resources\ResourceInterface;
use App\Resources\User;
use App\Social\Provider\SocialProviderInterface;
use Carbon\Carbon;

/**
 * Class VkUserHydrator
 * @package App\Hydrators\User
 */
class VkUserHydrator extends AbstractHydrator
{

    const FIELD_UID = 'uid';
    const FIELD_FIRST_NAME = 'first_name';
    const FIELD_LAST_NAME = 'last_name';
    const FIELD_PHOTO = 'photo_max';
    const FIELD_SEX = 'sex';
    const FIELD_BIRTH_DATE = 'bdate';

    /**
     * @param ResourceInterface $resource
     * @return array
     */
    public function extract(ResourceInterface $resource)
    {
        return [];
    }

    /**
     * @param array $data
     * @return ResourceInterface
     * @throws HydratorException
     */
    public function hydrate(array $data)
    {
        $user = new User;

        $data = $this->hydrateEmptyStringAsNull(self::FIELD_FIRST_NAME, $data);
        $data = $this->hydrateEmptyStringAsNull(self::FIELD_LAST_NAME, $data);
        $data = $this->hydrateEmptyStringAsNull(self::FIELD_PHOTO, $data);


        $user->populate(
            [
                User::FIELD_PROVIDER => SocialProviderInterface::PROVIDER_VK,
                User::FIELD_PROVIDER_ID => $data[SocialProviderInterface::FIELD_PROVIDER_ID],
                User::FIELD_FIRST_NAME => $data[self::FIELD_FIRST_NAME],
                User::FIELD_LAST_NAME => $data[self::FIELD_LAST_NAME],
                User::FIELD_PHOTO => $data[self::FIELD_PHOTO],
                User::FIELD_SEX => $this->makeUserSex($data),
            ]
        );

        $birthDate = $this->makeUserBirthDate($data[self::FIELD_BIRTH_DATE]);
        if ($birthDate instanceof Carbon) {
            $user->setBirthDate($birthDate);
        }

        return $user;
    }

    /**
     * @param $date
     * @return Carbon|null
     * @throws HydratorException
     */
    private function makeUserBirthDate($date)
    {
        if (!is_string($date)) {
            return null;
        }

        if ('' === $date) {
            return null;
        }

        if (1 === substr_count($date, '.')) {  // VK may return birthdate in d.m or d.m.Y format
            $date .= '.0000';
        }

        try {
            $birthday = (new Carbon())->createFromFormat('d.m.Y', $date);
        } catch (\InvalidArgumentException $exception) {
            throw new HydratorException($exception->getMessage(), $exception->getCode(), $exception);
        }

        return $birthday;
    }

    /**
     * @param  array $data
     * @return null|string
     */
    private function makeUserSex(array $data)
    {
        if (empty($data[self::FIELD_SEX])) {
            return null;
        }

        if (1 === (int)$data[self::FIELD_SEX]) {
            return User::SEX_FEMALE;
        }

        if (2 === (int)$data[self::FIELD_SEX]) {
            return User::SEX_MALE;
        }

        return null;
    }
}