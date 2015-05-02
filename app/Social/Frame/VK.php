<?php

namespace App\Social\Frame;

use App\Exceptions\SocialException;
use App\User;
use DateTime;
use InvalidArgumentException;

/**
 * Class VK
 * @package App\Social\Frame
 */
class VK implements SocialFrameInterface
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
    protected $name = 'vk';

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
     * @param array $input
     * @return User
     * @throws SocialException
     */
    public function getUser(array $input)
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
            $user_info = $this->parseApiResult($input[self::FIELD_API_RESULT]);
        }

        if (!$this->isUserInfoFull($user_info)) {
            $user_info = $this->getUserInfo($viewer_id);
        }

        if (!$this->isUserInfoFull($user_info)) {
            throw new SocialException('Not enough user data');
        }

        $user = new User(
            [
                User::FIELD_PROVIDER => $this->name,
                User::FIELD_PROVIDER_ID => $user_info[self::FIELD_UID],
                User::FIELD_FIRST_NAME => $user_info[self::FIELD_FIRST_NAME],
                User::FIELD_LAST_NAME => $user_info[self::FIELD_LAST_NAME],
                User::FIELD_SEX => $this->getUserSex($user_info),
                User::FIELD_BIRTH_DATE => $this->getUserBirthDate($user_info),
            ]
        );

        return $user;
    }

    /**
     * @param  array $user_info
     * @return bool
     */
    private function isUserInfoFull(array $user_info)
    {
        foreach ($this->required_fields as $field) {
            if (empty($user_info[$field])) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param $viewer_id
     * @return array
     * @throws \Novanova\VK\VKException
     */
    private function getUserInfo($viewer_id)
    {
        $user_info = [];
        $data = $this->vk->no_auth_api(
            'users.get',
            [
                'user_id' => $viewer_id,
                'fields' => 'uid,first_name,last_name,sex,photo_max,birthdate'
            ]
        );
        if (!empty($data[0])) {
            $user_info = (array)$data[0];
            $user_info[self::FIELD_UID] = $user_info['id'];
        }

        return $user_info;
    }

    /**
     * @param array $user_info
     * @return null|string
     */
    private function getUserSex(array $user_info)
    {
        if (empty($user_info[self::FIELD_SEX])) {
            return null;
        }
        if (1 == $user_info[self::FIELD_SEX]) {
            return User::SEX_FEMALE;
        }
        if (2 == $user_info[self::FIELD_SEX]) {
            return User::SEX_MALE;
        }
        return null;
    }

    /**
     * @param array $user_info
     * @return DateTime|null
     */
    private function getUserBirthDate(array $user_info)
    {
        if (empty($user_info[self::FIELD_BIRTH_DATE])) {
            return null;
        }
        $birthday = DateTime::createFromFormat('d.m.Y', $user_info[self::FIELD_BIRTH_DATE]);
        if ($birthday instanceof DateTime) {
            return $birthday;
        }
        $birthday = DateTime::createFromFormat('d.m.Y', $user_info[self::FIELD_BIRTH_DATE].'.0000');
        if ($birthday instanceof DateTime) {
            return $birthday;
        }
        return null;
    }

    /**
     * @param $data
     * @return array
     */
    private function parseApiResult($data)
    {
        $result = [];
        $json = json_decode($data, true);
        if (!$json) {
            return $result;
        }
        if (!empty($json['response'][0])) {
            $result = $json['response'][0];
        }

        return $result;
    }
}
