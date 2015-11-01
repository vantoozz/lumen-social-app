<?php

namespace App\Resources;

use Carbon\Carbon;
use DateTime;
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
     * @var string
     */
    protected $cdn_photo;
    /**
     * @var Carbon
     */
    protected $birth_date;

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
     * @param int $provider_id
     */
    public function setProviderId($provider_id)
    {
        $this->provider_id = $provider_id;
    }

    /**
     * @return string
     */
    public function getCdnPhoto()
    {
        return $this->cdn_photo;
    }

    /**
     * @param  string $cdn_photo
     *
     * @return User
     */
    public function setCdnPhoto($cdn_photo)
    {
        $this->cdn_photo = (string)$cdn_photo;

        return $this;
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
        return $this->birth_date;
    }

    /**
     * @param Carbon $birth_date
     */
    public function setBirthDate(Carbon $birth_date)
    {
        $this->birth_date = $birth_date;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * @param string $first_name
     */
    public function setFirstName($first_name)
    {
        $this->first_name = $first_name;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * @param string $last_name
     */
    public function setLastName($last_name)
    {
        $this->last_name = $last_name;
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
        if ('' === (string)$this->first_name) {
            return true;
        }

        return $this->isOutdated();
    }

    /**
     * @return bool
     */
    public function isOutdated()
    {
        $last_sync_at = $this->last_sync_at ?: $this->created_at;

        if (!$last_sync_at instanceof DateTime) {
            $last_sync_at = DateTime::createFromFormat(self::FORMAT_DATETIME, $last_sync_at);
        }

        if (!$last_sync_at) {
            return true;
        }

        return $last_sync_at->diff(new DateTime())->days >= self::SYNC_FREQUENCY;
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
}
