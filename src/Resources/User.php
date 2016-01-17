<?php

namespace App\Resources;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;

/**
 * Class User
 * @package App\Resources
 */
class User extends AbstractResource implements Authenticatable
{
    const FIELD_PROVIDER = 'provider';
    const FIELD_PROVIDER_ID = 'provider_id';
    const FIELD_FIRST_NAME = 'first_name';
    const FIELD_LAST_NAME = 'last_name';
    const FIELD_SEX = 'sex';
    const FIELD_PHOTO = 'photo';
    const FIELD_CDN_PHOTO = 'cdn_photo';
    const FIELD_BIRTH_DATE = 'birth_date';
    const FIELD_LAST_LOGIN_AT = 'last_login_at';
    const FIELD_LAST_SYNC_AT = 'last_sync_at';

    const SEX_MALE = 'male';
    const SEX_FEMALE = 'female';

    const SYNC_FREQUENCY = 7;

    /**
     * @var string
     */
    protected $provider;
    /**
     * @var int
     */
    protected $providerId;
    /**
     * @var string
     */
    protected $firstName;
    /**
     * @var string
     */
    protected $lastName;
    /**
     * @var string
     */
    protected $sex;
    /**
     * @var string
     */
    protected $photo;
    /**
     * @var string
     */
    protected $cdnPhoto;
    /**
     * @var Carbon
     */
    protected $birthDate;

    /**
     * @return int
     */
    public function getProviderId()
    {
        return $this->providerId;
    }

    /**
     * @return string
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * @param string $photo
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;
    }

    /**
     * @param string $provider
     */
    public function setProvider($provider)
    {
        $this->provider = $provider;
    }

    /**
     * @param int $providerId
     */
    public function setProviderId($providerId)
    {
        $this->providerId = (int)$providerId;
    }

    /**
     * @return string
     */
    public function getCdnPhoto()
    {
        return $this->cdnPhoto;
    }

    /**
     * @param  string $cdnPhoto
     *
     * @return User
     */
    public function setCdnPhoto($cdnPhoto)
    {
        $this->cdnPhoto = $cdnPhoto;
    }

    /**
     * @return string
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @return Carbon
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * @param Carbon $birthDate
     */
    public function setBirthDate(Carbon $birthDate)
    {
        $this->birthDate = $birthDate;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * @param string $sex
     */
    public function setSex($sex)
    {
        $this->sex = $sex;
    }

    /**
     * @return bool
     */
    public function isSyncNeeded()
    {
        return '' === (string)$this->firstName;
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->getId();
    }

    /**
     * Get the password for the user.
     *
     * @return string
     * @throws \LogicException
     */
    public function getAuthPassword()
    {
        throw new \LogicException('Invalid method');
    }

    /**
     * Get the token value for the "remember me" session.
     *
     * @return string
     * @throws \LogicException
     */
    public function getRememberToken()
    {
        throw new \LogicException('Invalid method');
    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @param  string $value
     *
     * @return void
     * @throws \LogicException
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function setRememberToken($value)
    {
        throw new \LogicException('Invalid method');
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     * @throws \LogicException
     */
    public function getRememberTokenName()
    {
        throw new \LogicException('Invalid method');
    }

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'id';
    }
}
