<?php

namespace App\Social\Frame;

/**
 * Interface SocialFrameInterface
 * @package App\Social\Frame
 */
use App\User;

/**
 * Interface SocialFrameInterface
 * @package App\Social\Frame
 */
interface SocialFrameInterface {

    /**
     * @param array $input
     * @return User
     */
    public function getUser(array $input);
}