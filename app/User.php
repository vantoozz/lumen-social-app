<?php

namespace App;

use DateTime;

/**
 * Class User
 * @package App
 */
class User extends AbstractModel
{
    const FIELD_PROVIDER = 'provider';
    const FIELD_PROVIDER_ID = 'provider_id';
    const FIELD_FIRST_NAME = 'first_name';
    const FIELD_LAST_NAME = 'last_name';
    const FIELD_SEX = 'sex';
    const FIELD_PHOTO = 'photo';
    const FIELD_BIRTH_DATE = 'birth_date';
    const FIELD_LAST_LOGIN_AT = 'last_login_at';

    const SEX_MALE = 'male';
    const SEX_FEMALE = 'female';


    /**
     * @var string
     */
    protected $provider;
    /**
     * @var int
     */
    protected $provider_id;
    /**
     * @var string
     */
    protected $first_name;
    /**
     * @var string
     */
    protected $last_name;
    /**
     * @var string
     */
    protected $sex;
    /**
     * @var string
     */
    protected $photo;
    /**
     * @var DateTime
     */
    protected $birth_date;
    /**
     * @var DateTime
     */
    protected $last_login_at;


    /**
     * @return int
     */
    public function getProviderId()
    {
        return $this->provider_id;
    }

    /**
     * @return string
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * @return $this
     */
    public function setLastLoginNow()
    {
        $this->last_login_at = new DateTime;
        return $this;
    }


    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return parent::toArray() + [
            self::FIELD_PROVIDER => $this->provider,
            self::FIELD_PROVIDER_ID => $this->provider_id,
            self::FIELD_FIRST_NAME => $this->first_name,
            self::FIELD_LAST_NAME => $this->last_name,
            self::FIELD_SEX => $this->sex,
            self::FIELD_PHOTO => $this->photo,
            self::FIELD_BIRTH_DATE => $this->birth_date,
            self::FIELD_LAST_LOGIN_AT => $this->formatDateTime($this->last_login_at),
        ];
    }
}