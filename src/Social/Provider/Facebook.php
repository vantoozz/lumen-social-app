<?php

namespace App\Social\Provider;

use App\Exceptions\HydratorException;
use App\Exceptions\NotAuthorizedException;
use App\Exceptions\SocialException;
use App\Hydrators\User\FacebookUserHydrator;
use App\Resources\User;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;

/**
 * Class Facebook
 * @package App\Social\Provider
 */
class Facebook implements SocialProviderInterface
{

    const PROVIDER_NAME = 'fb';

    /**
     * @var \Facebook\Facebook
     */
    private $facebook;
    /**
     * @var FacebookUserHydrator
     */
    private $hydrator;

    /**
     * Facebook constructor.
     * @param \Facebook\Facebook $facebook
     * @param FacebookUserHydrator $hydrator
     */
    public function __construct(\Facebook\Facebook $facebook, FacebookUserHydrator $hydrator)
    {
        $this->facebook = $facebook;
        $this->hydrator = $hydrator;
    }

    /**
     * @param  array $input
     * @return \App\Resources\User
     * @throws SocialException
     * @throws NotAuthorizedException
     */
    public function getFrameUser(array $input)
    {
        $helper = $this->facebook->getCanvasHelper();

        try {
            $accessToken = $helper->getAccessToken();
        } catch (FacebookResponseException $e) {
            throw new SocialException($e->getMessage(), $e->getCode(), $e);
        } catch (FacebookSDKException $e) {
            throw new SocialException($e->getMessage(), $e->getCode(), $e);
        }

        if (null === $accessToken) {
            $exception = new NotAuthorizedException('User has not authorized your app yet.');
            $exception->setProvider(self::PROVIDER_NAME);
            throw $exception;
        }

        $user = new User(self::PROVIDER_NAME, $helper->getSignedRequest()->getUserId());
        $user->setAccessToken($accessToken->getValue());

        return $user;
    }

    /**
     * @param User $user
     * @return \App\Resources\User
     * @throws SocialException
     * @throws HydratorException
     */
    public function fillUserData(User $user)
    {
        $providerId = $user->getProviderId();
        $accessToken = $user->getAccessToken();

        try {
            $userData = $this->getFacebookUser($providerId, $accessToken)->asArray();
            $userData[User::FIELD_PHOTO] = $this->getFacebookUserPicture($providerId, $accessToken);
        } catch (FacebookSDKException $e) {
            throw new SocialException($e->getMessage(), $e->getCode(), $e);
        }

        $userData[SocialProviderInterface::FIELD_PROVIDER_ID] = $providerId;

        /** @var User $this ->facebookUser */
        $this->facebookUser = $this->hydrator->hydrate($userData);

        $user->setFirstName($this->facebookUser->getFirstName());
        $user->setLastName($this->facebookUser->getLastName());
        $user->setSex($this->facebookUser->getSex());
        $user->setPhoto($this->facebookUser->getPhoto());
    }

    /**
     * @param $providerId
     * @param $accessToken
     * @return \Facebook\GraphNodes\GraphUser
     * @throws FacebookSDKException
     */
    private function getFacebookUser($providerId, $accessToken)
    {
        return $this->facebook
            ->get('/' . $providerId . '?fields=id,first_name,last_name,gender', $accessToken)
            ->getGraphUser();
    }

    /**
     * @param $providerId
     * @param $accessToken
     * @return \Facebook\GraphNodes\GraphUser
     * @throws FacebookSDKException
     */
    private function getFacebookUserPicture($providerId, $accessToken)
    {
        return $this->facebook
            ->get('/' . $providerId . '/picture?type=large&redirect=false', $accessToken)
            ->getGraphNode()
            ->getField('url', null);
    }

    /**
     * @param User $user
     * @return string
     * @throws SocialException
     */
    public function getLongLivedAccessToken(User $user)
    {
        $oAuth2Client = $this->facebook->getOAuth2Client();

        try {
            $accessToken = $oAuth2Client->getLongLivedAccessToken($user->getAccessToken());
        } catch (FacebookSDKException $e) {
            throw new SocialException($e->getMessage(), $e->getCode(), $e);
        }

        return $accessToken->getValue();
    }
}
