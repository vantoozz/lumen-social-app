<?php

namespace App\Jobs;

use App\Social\Provider\VK;
use App\User;
use DateTime;
use Illuminate\Contracts\Bus\SelfHandling;
use Laravel\Lumen\Routing\DispatchesCommands;

/**
 * Class HandleVKFrameApiCall
 * @package App\Jobs
 */
class HandleVKFrameApiCall implements SelfHandling
{

    use DispatchesCommands;

    /**
     * @var array
     */
    private $data;

    /**
     * @var int
     */
    private $viewer_id;

    /**
     * @param array $data
     * @param $viewer_id
     */
    public function __construct(array $data, $viewer_id)
    {
        $this->data = $data;
        $this->viewer_id = $viewer_id;
    }

    /**
     *
     */
    public function handle()
    {
        $user_info = [];
        if (!empty($this->data[VK::FIELD_FIRST_NAME])) {
            $user_info[User::FIELD_FIRST_NAME] = $this->data[VK::FIELD_FIRST_NAME];
        }
        if (!empty($this->data[VK::FIELD_LAST_NAME])) {
            $user_info[User::FIELD_LAST_NAME] = $this->data[VK::FIELD_LAST_NAME];
        }
        $user_info[User::FIELD_BIRTH_DATE] = $this->getUserBirthDate($this->data);
        $user_info[User::FIELD_SEX] = $this->getUserSex($this->data);

        if (!empty($user_info)) {
            $job = new UpdateSocialUser(VK::PROVIDER_VK, $this->viewer_id, $user_info);
            $this->dispatch($job);
        }
    }

    /**
     * @param  array $data
     * @return DateTime|null
     */
    private function getUserBirthDate(array $data)
    {
        if (empty($data[VK::FIELD_BIRTH_DATE])) {
            return null;
        }
        $birthday = DateTime::createFromFormat('d.m.Y', $data[VK::FIELD_BIRTH_DATE]);
        if ($birthday instanceof DateTime) {
            return $birthday;
        }
        $birthday = DateTime::createFromFormat('d.m.Y', $data[VK::FIELD_BIRTH_DATE] . '.0000');
        if ($birthday instanceof DateTime) {
            return $birthday;
        }

        return null;
    }

    /**
     * @param  array $data
     * @return null|string
     */
    private function getUserSex(array $data)
    {
        if (empty($data[VK::FIELD_SEX])) {
            return null;
        }
        if (1 == $data[VK::FIELD_SEX]) {
            return User::SEX_FEMALE;
        }
        if (2 == $data[VK::FIELD_SEX]) {
            return User::SEX_MALE;
        }

        return null;
    }

}
