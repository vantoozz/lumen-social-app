<?php declare(strict_types = 1);

namespace App\Hydrators\User;

use App\Exceptions\HydratorException;
use App\Hydrators\AbstractHydrator;
use App\Resources\ResourceInterface;
use App\Resources\User;
use App\Social\Provider\SocialProviderInterface;
use App\Social\Provider\VK;
use Carbon\Carbon;

/**
 * Class VkUserHydrator
 * @package App\Hydrators\User
 */
class VkUserHydrator extends AbstractHydrator
{

    const VK_SEX_MALE = 2;
    const VK_SEX_FEMALE = 1;

    const FIELD_UID = 'uid';
    const FIELD_FIRST_NAME = 'first_name';
    const FIELD_LAST_NAME = 'last_name';
    const FIELD_PHOTO = 'photo_max';
    const FIELD_SEX = 'sex';
    const FIELD_BIRTH_DATE = 'bdate';

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

        $user = new User(VK::PROVIDER_VK, $data[SocialProviderInterface::FIELD_PROVIDER_ID]);

        $data = $this->hydrateEmptyAsNull(self::FIELD_FIRST_NAME, $data);
        $data = $this->hydrateEmptyAsNull(self::FIELD_LAST_NAME, $data);
        $data = $this->hydrateEmptyAsNull(self::FIELD_PHOTO, $data);


        $user->populate(
            [
                User::FIELD_FIRST_NAME => $data[self::FIELD_FIRST_NAME],
                User::FIELD_LAST_NAME => $data[self::FIELD_LAST_NAME],
                User::FIELD_PHOTO => $data[self::FIELD_PHOTO],
                User::FIELD_SEX => $this->makeUserSex($data),
            ]
        );

        $birthDate = $this->makeUserBirthDate($data);
        if ($birthDate instanceof Carbon) {
            $user->setBirthDate($birthDate);
        }

        return $user;
    }

    /**
     * @param array $data
     * @return Carbon|null
     * @throws HydratorException
     */
    private function makeUserBirthDate(array $data)
    {
        if (!array_key_exists(self::FIELD_BIRTH_DATE, $data)) {
            return null;
        }

        $date = $data[self::FIELD_BIRTH_DATE];

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
        } catch (\InvalidArgumentException $e) {
            throw new HydratorException($e->getMessage(), $e->getCode(), $e);
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

        if (self::VK_SEX_FEMALE === (int)$data[self::FIELD_SEX]) {
            return User::SEX_FEMALE;
        }

        if (self::VK_SEX_MALE === (int)$data[self::FIELD_SEX]) {
            return User::SEX_MALE;
        }

        return null;
    }
}
