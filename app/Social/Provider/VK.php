<?php

namespace App\Social\Provider;

use App\Exceptions\SocialException;
use App\Jobs\HandleVKFrameApiCall;
use App\User;
use InvalidArgumentException;
use Laravel\Lumen\Routing\DispatchesCommands;

/**
 * Class VK
 * @package App\Social\Provider
 */
class VK implements SocialProviderInterface
{

    use DispatchesCommands;

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

        if (!empty($input[self::FIELD_API_RESULT])) {
            $user_info = $this->getFrameApiCallResult($input[self::FIELD_API_RESULT]);

            $job = new HandleVKFrameApiCall($user_info, $viewer_id);
            $this->dispatch($job);
        }

        $user = new User(
            [
                User::FIELD_PROVIDER => $this->provider_name,
                User::FIELD_PROVIDER_ID => $viewer_id,
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

}
