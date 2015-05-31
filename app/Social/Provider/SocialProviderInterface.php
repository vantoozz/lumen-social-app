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
     * @param array $input
     * @return \App\User
     */
    public function getFrameUser(array $input);

    /**
     * @param int $provider_id
     * @return \App\User
     */
    public function getUserByProviderId($provider_id);


    /**
     * @return string
     */
    public function getProviderName();
}