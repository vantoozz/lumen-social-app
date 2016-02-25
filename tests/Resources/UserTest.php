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
        $user = new User('provider', 123);
        static::assertSame('provider', $user->getProvider());
    }

    /**
     * @test
     */
    public function it_stores_provider_id()
    {
        $user = new User('provider', 123);
        static::assertSame(123, $user->getProviderId());
    }

    /**
     * @test
     */
    public function it_stores_provider_id_as_integer()
    {
        $user = new User('provider', '123');
        static::assertSame(123, $user->getProviderId());
    }

    /**
     * @test
     */
    public function it_stores_id()
    {
        $user = new User('provider', 123);
        $user->setId(123);
        static::assertSame(123, $user->getId());
    }

    /**
     * @test
     */
    public function it_stores_id_as_integer()
    {
        $user = new User('provider', 123);
        $user->setId('123');
        static::assertSame(123, $user->getId());
    }

    /**
     * @test
     */
    public function it_stores_first_name()
    {
        $user = new User('provider', 123);
        $user->setFirstName('value');
        static::assertSame('value', $user->getFirstName());
    }

    /**
     * @test
     */
    public function it_stores_last_name()
    {
        $user = new User('provider', 123);
        $user->setLastName('value');
        static::assertSame('value', $user->getLastName());
    }

    /**
     * @test
     */
    public function it_stores_photo()
    {
        $user = new User('provider', 123);
        $user->setPhoto('value');
        static::assertSame('value', $user->getPhoto());
    }

    /**
     * @test
     */
    public function it_stores_cdn_photo()
    {
        $user = new User('provider', 123);
        $user->setCdnPhoto('value');
        static::assertSame('value', $user->getCdnPhoto());
    }

    /**
     * @test
     */
    public function it_stores_sex()
    {
        $user = new User('provider', 123);
        $user->setSex('value');
        static::assertSame('value', $user->getSex());
    }

    /**
     * @test
     */
    public function it_stores_birth_date()
    {
        $user = new User('provider', 123);
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
        $user = new User('provider', 123);
        $user->setRememberToken('value');
    }

    /**
     * @test
     * @expectedException     \LogicException
     * @expectedExceptionMessage Invalid method
     */
    public function it_do_not_gets_remember_token()
    {
        $user = new User('provider', 123);
        $user->getRememberToken();
    }

    /**
     * @test
     * @expectedException     \LogicException
     * @expectedExceptionMessage Invalid method
     */
    public function it_do_not_gets_remember_token_name()
    {
        $user = new User('provider', 123);
        $user->getRememberTokenName();
    }

    /**
     * @test
     * @expectedException     \LogicException
     * @expectedExceptionMessage Invalid method
     */
    public function it_do_not_gets_auth_password()
    {
        $user = new User('provider', 123);
        $user->getAuthPassword();
    }

    /**
     * @test
     */
    public function it_gets_auth_identifier()
    {
        $user = new User('provider', 111);
        $user->setId(123);
        static::assertSame(123, $user->getAuthIdentifier());
    }

    /**
     * @test
     */
    public function it_populates_with_data()
    {
        $user = new User('provider', 123);
        $user->populate(['first_name' => 'first name', 'last_name' => 'last name']);
        static::assertSame('first name', $user->getFirstName());
        static::assertSame('last name', $user->getLastName());
    }

    /**
     * @test
     */
    public function it_returns_auth_identifier_name()
    {
        $user = new User('provider', 123);
        static::assertSame('id', $user->getAuthIdentifierName());
    }
}
