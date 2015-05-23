<?php

namespace App\Jobs;

use App\Exceptions\JobException;
use App\Exceptions\NotFoundInRepositoryException;
use Illuminate\Contracts\Bus\SelfHandling;

/**
 * Class UpdateSocialUser
 * @package App\Jobs
 */
class UpdateSocialUser implements SelfHandling
{
    /**
     * @var string
     */
    private $provider;
    /**
     * @var int
     */
    private $provider_id;

    /**
     * @var array
     */
    private $data;

    /**
     * @param $provider
     * @param $provider_id
     * @param array $data
     */
    public function __construct($provider, $provider_id, array $data = [])
    {
        $this->provider = $provider;
        $this->provider_id = $provider_id;
        $this->data = $data;
    }

    /**
     *
     */
    public function handle()
    {
        /** @var \App\Repositories\Users\UsersRepositoryInterface $usersRepository */
        $usersRepository = app('App\Repositories\Users\UsersRepositoryInterface');

        try {
            $user = $usersRepository->getByProviderId($this->provider, $this->provider_id);
        } catch (NotFoundInRepositoryException $e) {
            throw new JobException($e->getMessage());
        }

        $user->fill($this->data);
        $usersRepository->save($user);
    }

}
