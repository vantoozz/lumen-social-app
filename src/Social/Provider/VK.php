<?php

namespace App\Social\Provider;

use App\Exceptions\HydratorException;
use App\Exceptions\SocialException;
use App\Hydrators\User\VkUserHydrator;
use App\Resources\User;

/**
 * Class VK
 * @package App\Social\Provider
 */
class VK implements SocialProviderInterface
{
    const PROVIDER_NAME = 'vk';

    const FIELD_VIEWER_ID = 'viewer_id';
    const FIELD_AUTH_KEY = 'auth_key';
    const FIELD_API_RESULT = 'api_result';

    /** @var \Novanova\VK\VK $driver */
    private $driver;

    /**
     * @var VkUserHydrator
     */
    private $hydrator;

    /**
     * @param \Novanova\VK\VK $driver
     * @param VkUserHydrator $hydrator
     */
    public function __construct(\Novanova\VK\VK $driver, VkUserHydrator $hydrator)
    {
        $this->driver = $driver;
        $this->hydrator = $hydrator;
    }

    /**
     * @param  array $input
     * @return User
     * @throws SocialException
     * @throws HydratorException
     */
    public function getFrameUser(array $input)
    {
        if (empty($input[self::FIELD_VIEWER_ID])) {
            throw new SocialException('No viewer_id field');
        }

        if (empty($input[self::FIELD_AUTH_KEY])) {
            throw new SocialException('No auth_key field');
        }

        $viewerId = $input[self::FIELD_VIEWER_ID];
        $authKey = $input[self::FIELD_AUTH_KEY];

        if ($this->driver->calculateAuthKey($viewerId) !== $authKey) {
            throw new SocialException('Bad auth key');
        }

        $userData = [];
        if (!empty($input[self::FIELD_API_RESULT])) {
            $userData = $this->getFrameApiCallResult($input[self::FIELD_API_RESULT]);
        }

        $userData[self::FIELD_PROVIDER_ID] = $viewerId;

        return $this->hydrator->hydrate($userData);
    }

    /**
     * @param $apiCallResult
     * @return array
     */
    private function getFrameApiCallResult($apiCallResult)
    {
        $data = [];
        $json = json_decode($apiCallResult, true);
        if (!$json) {
            return $data;
        }
        if (!empty($json['response'][0])) {
            $data = $json['response'][0];
        }

        return $data;
    }

    /**
     * @param  int $providerId
     * @param string $accessToken
     * @return User
     * @throws HydratorException
     */
    public function getUserByProviderId($providerId, $accessToken)
    {
        $userData = [];
        $data = $this->driver->no_auth_api(
            'users.get',
            [
                'user_id' => $providerId,
                'fields' => 'uid,first_name,last_name,sex,photo_max,bdate'
            ]
        );
        if (!empty($data[0])) {
            $userData = (array)$data[0];
        }

        $userData[self::FIELD_PROVIDER_ID] = $providerId;
        $user = $this->hydrator->hydrate($userData);

        return $user;
    }
}
