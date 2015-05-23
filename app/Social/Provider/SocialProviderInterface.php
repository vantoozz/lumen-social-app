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
     * @return string
     */
    public function getProviderName();
}