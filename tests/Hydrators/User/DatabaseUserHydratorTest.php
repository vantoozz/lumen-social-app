<?php declare(strict_types = 1);

namespace App\Hydrators\User;

use App\Resources\User;
use App\TestCase;
use Carbon\Carbon;

class DatabaseUserHydratorTest extends TestCase
{

    /**
     * @test
     */
    public function it_extracts_a_user()
    {
        $hydrator = new DatabaseUserHydrator;

        $user = new User('provider', 12345);
        $user->populate([
            User::FIELD_ID => 123,
            User::FIELD_FIRST_NAME => 'first_name',
            User::FIELD_LAST_NAME => 'last_name',
            User::FIELD_SEX => 'sex',
            User::FIELD_PHOTO => 'photo',
            User::FIELD_CDN_PHOTO => 'cdn_photo',
            User::FIELD_BIRTH_DATE => new Carbon('2015-01-02 11:22:33'),
            User::FIELD_LAST_LOGIN_AT => new Carbon('2015-01-02 11:33:44'),
            User::FIELD_LAST_SYNC_AT => new Carbon('2015-01-02 11:44:55'),
        ]);

        static::assertSame([
            'id' => 123,
            'provider' => 'provider',
            'provider_id' => 12345,
            'first_name' => 'first_name',
            'last_name' => 'last_name',
            'sex' => 'sex',
            'photo' => 'photo',
            'cdn_photo' => 'cdn_photo',
            'birth_date' => '2015-01-02',
        ], $hydrator->extract($user));
    }

    /**
     * @test
     */
    public function it_extracts_a_user_with_no_birth_date()
    {
        $hydrator = new DatabaseUserHydrator;

        $user = new User('provider', 12345);
        $user->populate([
            User::FIELD_ID => 123,
        ]);

        $data = $hydrator->extract($user);
        static::assertNull($data['birth_date']);
    }


    /**
     * @test
     */
    public function it_hydrates_a_user()
    {
        $hydrator = new DatabaseUserHydrator;

        $user = $hydrator->hydrate([
            'id' => 123,
            'provider' => 'provider',
            'provider_id' => 12345,
            'first_name' => 'first_name',
            'last_name' => 'last_name',
            'sex' => 'sex',
            'photo' => 'photo',
            'cdn_photo' => 'cdn_photo',
            'birth_date' => '2015-01-02',
        ]);

        static::assertInstanceOf(User::class, $user);

        static::assertSame(123, $user->getId());
        static::assertSame('provider', $user->getProvider());
        static::assertSame(12345, $user->getProviderId());
        static::assertSame('first_name', $user->getFirstName());
        static::assertSame('last_name', $user->getLastName());
        static::assertSame('sex', $user->getSex());
        static::assertSame('photo', $user->getPhoto());
        static::assertSame('cdn_photo', $user->getCdnPhoto());
        static::assertSame('20150102', $user->getBirthDate()->format('Ymd'));

    }

    /**
     * @test
     */
    public function it_hydrates_a_user_without_birth_date()
    {
        $hydrator = new DatabaseUserHydrator;

        $user = $hydrator->hydrate([
            'provider_id' => 123,
            'provider' => 'provider'
        ]);

        static::assertNull($user->getBirthDate());
    }

    /**
     * @test
     * @expectedException     \App\Exceptions\HydratorException
     * @expectedExceptionMessage A four digit year could not be found
     */
    public function it_throws_exception_if_bad_date_format()
    {
        $hydrator = new DatabaseUserHydrator;

        $hydrator->hydrate([
            'provider_id' => 123,
            'provider' => 'provider',
            'birth_date' => 'bad formatted date',
        ]);
    }

    /**
     * @test
     * @expectedException     \App\Exceptions\HydratorException
     * @expectedExceptionMessage No provider field
     */
    public function it_throws_exception_if_no_provider_field()
    {
        $hydrator = new DatabaseUserHydrator;

        $hydrator->hydrate([
            'provider_id' => 123,
        ]);
    }

    /**
     * @test
     * @expectedException     \App\Exceptions\HydratorException
     * @expectedExceptionMessage No provider_id field
     */
    public function it_throws_exception_if_no_provider_id_field()
    {
        $hydrator = new DatabaseUserHydrator;

        $hydrator->hydrate([
            'provider' => 'provider',
        ]);
    }
}
