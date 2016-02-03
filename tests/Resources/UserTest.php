<?php

namespace App\Resources;

use App\TestCase;
use Carbon\Carbon;

class UserTest extends TestCase
{
    /**
     * @test
     */
    public function it_stores_provider()
    {
        $user = new User;
        $user->setProvider('value');
        static::assertSame('value', $user->getProvider());
    }

    /**
     * @test
     */
    public function it_stores_provider_id()
    {
        $user = new User;
        $user->setProviderId(123);
        static::assertSame(123, $user->getProviderId());
    }

    /**
     * @test
     */
    public function it_stores_provider_id_as_integer()
    {
        $user = new User;
        $user->setProviderId('123');
        static::assertSame(123, $user->getProviderId());
    }

    /**
     * @test
     */
    public function it_stores_id()
    {
        $user = new User;
        $user->setId(123);
        static::assertSame(123, $user->getId());
    }

    /**
     * @test
     */
    public function it_stores_id_as_integer()
    {
        $user = new User;
        $user->setId('123');
        static::assertSame(123, $user->getId());
    }

    /**
     * @test
     */
    public function it_stores_first_name()
    {
        $user = new User;
        $user->setFirstName('value');
        static::assertSame('value', $user->getFirstName());
    }

    /**
     * @test
     */
    public function it_stores_last_name()
    {
        $user = new User;
        $user->setLastName('value');
        static::assertSame('value', $user->getLastName());
    }

    /**
     * @test
     */
    public function it_stores_photo()
    {
        $user = new User;
        $user->setPhoto('value');
        static::assertSame('value', $user->getPhoto());
    }

    /**
     * @test
     */
    public function it_stores_cdn_photo()
    {
        $user = new User;
        $user->setCdnPhoto('value');
        static::assertSame('value', $user->getCdnPhoto());
    }

    /**
     * @test
     */
    public function it_stores_sex()
    {
        $user = new User;
        $user->setSex('value');
        static::assertSame('value', $user->getSex());
    }

    /**
     * @test
     */
    public function it_stores_birth_date()
    {
        $user = new User;
        $date = new Carbon;
        $user->setBirthDate($date);
        static::assertSame($date, $user->getBirthDate());
    }

    /**
     * @test
     * @expectedException     \LogicException
     * @expectedExceptionMessage Invalid method
     */
    public function it_do_not_sets_remember_token()
    {
        $user = new User;
        $user->setRememberToken('value');
    }

    /**
     * @test
     * @expectedException     \LogicException
     * @expectedExceptionMessage Invalid method
     */
    public function it_do_not_gets_remember_token()
    {
        $user = new User;
        $user->getRememberToken();
    }

    /**
     * @test
     * @expectedException     \LogicException
     * @expectedExceptionMessage Invalid method
     */
    public function it_do_not_gets_remember_token_name()
    {
        $user = new User;
        $user->getRememberTokenName();
    }

    /**
     * @test
     * @expectedException     \LogicException
     * @expectedExceptionMessage Invalid method
     */
    public function it_do_not_gets_auth_password()
    {
        $user = new User;
        $user->getAuthPassword();
    }

    /**
     * @test
     */
    public function it_gets_auth_identifier()
    {
        $user = new User;
        $user->setId(123);
        static::assertSame(123, $user->getAuthIdentifier());
    }

    /**
     * @test
     */
    public function it_populates_with_data()
    {
        $user = new User;
        $user->populate(['provider' => 'some provider', 'provider_id' => '123']);
        static::assertSame('some provider', $user->getProvider());
        static::assertSame(123, $user->getProviderId());
    }

    /**
     * @test
     */
    public function it_needs_sync_if_first_name_is_empty()
    {
        $user = new User;
        $user->setFirstName('');
        static::assertTrue($user->isSyncNeeded());
    }

    /**
     * @test
     */
    public function it_do_not_needs_sync_if_first_name_is_not_empty()
    {
        $user = new User;
        $user->setFirstName('value');
        static::assertFalse($user->isSyncNeeded());
    }

    /**
     * @test
     */
    public function it_returns_auth_identifier_name()
    {
        $user = new User;
        static::assertSame('id', $user->getAuthIdentifierName());
    }

}
