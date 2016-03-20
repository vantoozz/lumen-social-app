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
     * @param  int $providerId
     * @param string $accessToken
     * @return \App\Resources\User
     * @throws SocialException
     * @throws HydratorException
     */
    public function getUserByProviderId($providerId, $accessToken)
    {
        try {
            $data = $this->getFacebookUser($providerId, $accessToken)->asArray();
            $data[User::FIELD_PHOTO] = $this->getFacebookUserPicture($providerId, $accessToken);
        } catch (FacebookSDKException $e) {
            throw new SocialException($e->getMessage(), $e->getCode(), $e);
        }

        $data[SocialProviderInterface::FIELD_PROVIDER_ID] = $providerId;

        return $this->hydrator->hydrate($data);
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
}
