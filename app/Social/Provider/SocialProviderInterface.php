<?php

namespace App\Social\Provider;

/**
 * Interface SocialProviderInterface
 * @package App\Social\Provider
 */
interface SocialProviderInterface
{

    const PROVIDER_VK = 'vk';

    /**
     * @param  array $input
     * @return \App\Resources\User
     */
    public function getFrameUser(array $input);

    /**
     * @param  int $provider_id
     * @return \App\Resources\User
     */
    public function getUserByProviderId($provider_id);
}
