<?php

namespace App\Social\Provider;

use App\Exceptions\NotAuthorizedException;

/**
 * Interface SocialProviderInterface
 * @package App\Social\Provider
 */
interface SocialProviderInterface
{

    const FIELD_PROVIDER_ID = 'provider_id';

    /**
     * @param  array $input
     * @return \App\Resources\User
     * @throws NotAuthorizedException
     */
    public function getFrameUser(array $input);

    /**
     * @param  int $providerId
     * @param string $accessToken
     * @return \App\Resources\User
     */
    public function getUserByProviderId($providerId, $accessToken);
}
