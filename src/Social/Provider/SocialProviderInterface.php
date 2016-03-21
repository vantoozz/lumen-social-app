<?php

namespace App\Social\Provider;

use App\Exceptions\NotAuthorizedException;
use App\Resources\User;

/**
 * Interface SocialProviderInterface
 * @package App\Social\Provider
 */
interface SocialProviderInterface
{

    const FIELD_PROVIDER_ID = 'provider_id';

    /**
     * @param  array $input
     * @return User
     * @throws NotAuthorizedException
     */
    public function getFrameUser(array $input);

    /**
     * @param User $user
     */
    public function fillUserData(User $user);

    /**
     * @param User $user
     * @return string
     */
    public function getLongLivedAccessToken(User $user);
}
