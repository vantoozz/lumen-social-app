<?php

namespace App\Social\Provider;

use App\Exceptions\SocialException;
use App\Hydrators\HydratorInterface;
use App\Resources\User;

/**
 * Class VK
 * @package App\Social\Provider
 */
class VK implements SocialProviderInterface
{
    const FIELD_VIEWER_ID = 'viewer_id';
    const FIELD_AUTH_KEY = 'auth_key';
    const FIELD_API_RESULT = 'api_result';

    /** @var \Novanova\VK\VK $vk */
    private $vk;
    /**
     * @var HydratorInterface
     */
    private $hydrator;

    /**
     * @param \Novanova\VK\VK $vk
     * @param HydratorInterface $hydrator
     */
    public function __construct(\Novanova\VK\VK $vk, HydratorInterface $hydrator)
    {
        $this->vk = $vk;
        $this->hydrator = $hydrator;
    }

    /**
     * @param  array $input
     * @return User
     * @throws SocialException
     */
    public function getFrameUser(array $input)
    {
        if (empty($input[self::FIELD_VIEWER_ID])) {
            throw new SocialException('No viewer_id field');
        }

        if (empty($input[self::FIELD_AUTH_KEY])) {
            throw new SocialException('No auth_key field');
        }

        $viewer_id = $input[self::FIELD_VIEWER_ID];
        $auth_key = $input[self::FIELD_AUTH_KEY];

        if ($this->vk->calculateAuthKey($viewer_id) !== $auth_key) {
            throw new SocialException('Bad auth key');
        }

        $userData = [];
        if (!empty($input[self::FIELD_API_RESULT])) {
            $userData = $this->getFrameApiCallResult($input[self::FIELD_API_RESULT]);
        }

        $userData[self::FIELD_PROVIDER_ID] = $viewer_id;
        $user = $this->hydrator->hydrate($userData);

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
     * @param  int $provider_id
     * @return \App\Resources\User
     */
    public function getUserByProviderId($provider_id)
    {
        $userData = [];
        $data = $this->vk->no_auth_api(
            'users.get',
            [
                'user_id' => $provider_id,
                'fields' => 'uid,first_name,last_name,sex,photo_max,bdate'
            ]
        );
        if (!empty($data[0])) {
            $userData = (array)$data[0];
        }

        $userData[self::FIELD_PROVIDER_ID] = $provider_id;
        $user = $this->hydrator->hydrate($userData);

        return $user;
    }

}
