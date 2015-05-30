<?php

namespace App\Social\Provider;

use App\Exceptions\SocialException;
use App\User;
use DateTime;
use InvalidArgumentException;

/**
 * Class VK
 * @package App\Social\Provider
 */
class VK implements SocialProviderInterface
{

    const FIELD_UID = 'uid';
    const FIELD_FIRST_NAME = 'first_name';
    const FIELD_LAST_NAME = 'last_name';
    const FIELD_PHOTO = 'photo_max';
    const FIELD_SEX = 'sex';
    const FIELD_BIRTH_DATE = 'bdate';

    const FIELD_VIEWER_ID = 'viewer_id';
    const FIELD_AUTH_KEY = 'auth_key';
    const FIELD_API_RESULT = 'api_result';

    /**
     * @var string
     */
    protected $provider_name = self::PROVIDER_VK;

    /** @var \Novanova\VK\VK $vk */
    private $vk;

    /**
     * @var array
     */
    private $required_fields = [
        self::FIELD_UID,
        self::FIELD_FIRST_NAME,
        self::FIELD_LAST_NAME,
        self::FIELD_PHOTO,
        self::FIELD_BIRTH_DATE,
    ];

    /**
     * @param \Novanova\VK\VK $vk
     */
    public function __construct(\Novanova\VK\VK $vk)
    {
        $this->vk = $vk;
    }

    /**
     * @return string
     */
    public function getProviderName()
    {
        return $this->provider_name;
    }


    /**
     * @param array $input
     * @return User
     * @throws SocialException
     */
    public function getFrameUser(array $input)
    {
        if (empty($input[self::FIELD_VIEWER_ID])) {
            throw new InvalidArgumentException('No viewer_id field');
        }

        if (empty($input[self::FIELD_AUTH_KEY])) {
            throw new InvalidArgumentException('No auth_key field');
        }

        $viewer_id = $input[self::FIELD_VIEWER_ID];
        $auth_key = $input[self::FIELD_AUTH_KEY];

        if ($this->vk->calculateAuthKey($viewer_id) != $auth_key) {
            throw new InvalidArgumentException('Bad auth key');
        }

        $user_info = [];
        if (!empty($input[self::FIELD_API_RESULT])) {
            $user_info = $this->getFrameApiCallResult($input[self::FIELD_API_RESULT]);
        }

        $user = new User(
            [
                User::FIELD_PROVIDER => $this->provider_name,
                User::FIELD_PROVIDER_ID => $viewer_id,
                User::FIELD_FIRST_NAME => $user_info[self::FIELD_FIRST_NAME],
                User::FIELD_LAST_NAME => $user_info[self::FIELD_LAST_NAME],
                User::FIELD_SEX => $this->getUserSex($user_info),
                User::FIELD_BIRTH_DATE => $this->getUserBirthDate($user_info),
            ]
        );

        return $user;
    }

    /**
     * @param $api_call_result
     * @return array
     */
    private function getFrameApiCallResult($api_call_result)
    {
        $data = [];
        $json = json_decode($api_call_result, true);
        if (!$json) {
            return $data;
        }
        if (!empty($json['response'][0])) {
            $data = $json['response'][0];
        }

        return $data;
    }


    /**
     * @param array $data
     * @return DateTime|null
     */
    private function getUserBirthDate(array $data)
    {
        if (empty($data[VK::FIELD_BIRTH_DATE])) {
            return null;
        }
        $birthday = DateTime::createFromFormat('d.m.Y', $data[VK::FIELD_BIRTH_DATE]);
        if ($birthday instanceof DateTime) {
            return $birthday;
        }
        $birthday = DateTime::createFromFormat('d.m.Y', $data[VK::FIELD_BIRTH_DATE] . '.0000');
        if ($birthday instanceof DateTime) {
            return $birthday;
        }
        return null;
    }

    /**
     * @param array $data
     * @return null|string
     */
    private function getUserSex(array $data)
    {
        if (empty($data[VK::FIELD_SEX])) {
            return null;
        }
        if (1 == $data[VK::FIELD_SEX]) {
            return User::SEX_FEMALE;
        }
        if (2 == $data[VK::FIELD_SEX]) {
            return User::SEX_MALE;
        }
        return null;
    }

}
