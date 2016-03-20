<?php

namespace App\Hydrators\User;

use App\Exceptions\HydratorException;
use App\Hydrators\AbstractHydrator;
use App\Resources\ResourceInterface;
use App\Resources\User;
use App\Social\Provider\Facebook;
use App\Social\Provider\SocialProviderInterface;

/**
 * Class FacebookUserHydrator
 * @package App\Hydrators\User
 */
class FacebookUserHydrator extends AbstractHydrator
{

    const SEX_MALE = 'male';
    const SEX_FEMALE = 'female';

    const FIELD_FIRST_NAME = 'first_name';
    const FIELD_LAST_NAME = 'last_name';
    const FIELD_SEX = 'gender';

    /**
     * @param array $data
     * @return ResourceInterface
     * @throws HydratorException
     */
    public function hydrate(array $data)
    {
        if (!array_key_exists(SocialProviderInterface::FIELD_PROVIDER_ID, $data)) {
            throw new HydratorException('No provider_id field');
        }

        $user = new User(Facebook::PROVIDER_NAME, $data[SocialProviderInterface::FIELD_PROVIDER_ID]);

        $data = $this->hydrateEmptyAsNull(self::FIELD_FIRST_NAME, $data);
        $data = $this->hydrateEmptyAsNull(self::FIELD_LAST_NAME, $data);
        $data = $this->hydrateEmptyAsNull(User::FIELD_PHOTO, $data);


        $user->populate(
            [
                User::FIELD_FIRST_NAME => $data[self::FIELD_FIRST_NAME],
                User::FIELD_LAST_NAME => $data[self::FIELD_LAST_NAME],
                User::FIELD_PHOTO => $data[User::FIELD_PHOTO],
                User::FIELD_SEX => $this->makeUserSex($data),
            ]
        );


        return $user;
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

        if (self::SEX_FEMALE === $data[self::FIELD_SEX]) {
            return User::SEX_FEMALE;
        }

        if (self::SEX_MALE === $data[self::FIELD_SEX]) {
            return User::SEX_MALE;
        }

        return null;
    }
}
