<?php

namespace App\Hydrators\User;

use App\Exceptions\HydratorException;
use App\Hydrators\AbstractHydrator;
use App\Resources\ResourceInterface;
use App\Resources\User;

/**
 * Class DatabaseUserHydrator
 * @package App\Hydrators\User
 */
class DatabaseUserHydrator extends AbstractHydrator
{

    const FIELD_ID = 'id';
    const FIELD_PROVIDER = 'provider';
    const FIELD_PROVIDER_ID = 'provider_id';
    const FIELD_FIRST_NAME = 'first_name';
    const FIELD_LAST_NAME = 'last_name';
    const FIELD_SEX = 'sex';
    const FIELD_PHOTO = 'photo';
    const FIELD_CDN_PHOTO = 'cdn_photo';
    const FIELD_ACCESS_TOKEN = 'access_token';
    const FIELD_BIRTH_DATE = 'birth_date';
    const FIELD_LAST_LOGIN_AT = 'last_login_at';
    const FIELD_LAST_SYNC_AT = 'last_sync_at';

    /**
     * @param ResourceInterface $user
     * @return array
     */
    public function extract(ResourceInterface $user)
    {
        /** @var User $user */
        return [
            self::FIELD_ID => $user->getId(),
            self::FIELD_PROVIDER => $user->getProvider(),
            self::FIELD_PROVIDER_ID => $user->getProviderId(),
            self::FIELD_FIRST_NAME => $user->getFirstName(),
            self::FIELD_LAST_NAME => $user->getLastName(),
            self::FIELD_SEX => $user->getSex(),
            self::FIELD_PHOTO => $user->getPhoto(),
            self::FIELD_CDN_PHOTO => $user->getCdnPhoto(),
            self::FIELD_ACCESS_TOKEN => $user->getAccessToken(),
            self::FIELD_BIRTH_DATE => $this->extractNullableDate($user->getBirthDate())
        ];
    }

    /**
     * @param array $data
     * @return User
     * @throws HydratorException
     */
    public function hydrate(array $data)
    {
        if (empty($data[self::FIELD_PROVIDER])) {
            throw new HydratorException('No ' . self::FIELD_PROVIDER . ' field');
        }

        if (empty($data[self::FIELD_PROVIDER_ID])) {
            throw new HydratorException('No ' . self::FIELD_PROVIDER_ID . ' field');
        }

        $data = $this->hydrateDate(self::FIELD_BIRTH_DATE, $data);

        $user = new User($data[self::FIELD_PROVIDER], $data[self::FIELD_PROVIDER_ID]);
        $user->populate($data);

        return $user;
    }
}
