<?php

namespace App\Exceptions;

/**
 * Class NotAuthorizedException
 * @package App\Exceptions
 */
class NotAuthorizedException extends SocialException
{
    /**
     * @var string
     */
    private $provider;

    /**
     * @return string
     */
    public function getProvider()
    {
        return (string)$this->provider;
    }

    /**
     * @param string $provider
     */
    public function setProvider($provider)
    {
        $this->provider = (string)$provider;
    }
}
