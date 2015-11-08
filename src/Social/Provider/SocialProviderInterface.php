<?php

namespace App\Social\Provider;

/**
 * Interface SocialProviderInterface
 * @package App\Social\Provider
 */
interface SocialProviderInterface
{

    const PROVIDER_VK = 'vk';

    const FIELD_PROVIDER_ID = 'provider_id';

    /**
     * @param  array $input
     * @return \App\Resources\User
     */
    public function getFrameUser(array $input);

    /**
     * @param  int $providerId
     * @return \App\Resources\User
     */
    public function getUserByProviderId($providerId);
}
