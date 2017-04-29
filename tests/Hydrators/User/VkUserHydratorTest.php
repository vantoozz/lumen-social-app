<?php declare(strict_types = 1);

namespace App\Hydrators\User;

use App\Resources\User;
use App\TestCase;

class VkUserHydratorTest extends TestCase
{
    /**
     * @test
     */
    public function it_extracts_a_user_as_empty_array()
    {
        $hydrator = new VkUserHydrator;
        static::assertSame([], $hydrator->extract(new User('some provider', 12345)));
    }

    /**
     * @test
     */
    public function it_hydrates_a_user_with_all_fields()
    {
        $hydrator = new VkUserHydrator;
        $user = $hydrator->hydrate([
            'provider_id' => 123,
            'first_name' => 'First',
            'last_name' => 'Last',
            'photo_max' => 'photo',
            'sex' => '2',
            'bdate' => '01.02.2003'
        ]);
        static::assertInstanceOf(User::class, $user);
        /** @var User $user */
        static::assertSame('vk', $user->getProvider());
        static::assertSame(123, $user->getProviderId());
        static::assertSame('First', $user->getFirstName());
        static::assertSame('Last', $user->getLastName());
        static::assertSame('male', $user->getSex());
        static::assertSame('2003-02-01', $user->getBirthDate()->format('Y-m-d'));
    }

    /**
     * @test
     */
    public function it_hydrates_a_user_with_no_extra_fields()
    {
        $hydrator = new VkUserHydrator;
        $user = $hydrator->hydrate([
            'provider_id' => 123,
        ]);
        static::assertInstanceOf(User::class, $user);
        /** @var User $user */
        static::assertSame('vk', $user->getProvider());
        static::assertSame(123, $user->getProviderId());
        static::assertNull($user->getFirstName());
        static::assertNull($user->getLastName());
        static::assertNull($user->getSex());
        static::assertNull($user->getPhoto());
        static::assertNull($user->getBirthDate());
    }

    /**
     * @test
     * @expectedException     \App\Exceptions\HydratorException
     * @expectedExceptionMessage No provider_id field
     */
    public function it_throws_exception_if_no_provider_id()
    {
        $hydrator = new VkUserHydrator;
        $hydrator->hydrate([
            'some_field' => 123,
        ]);
    }


    /**
     * @test
     */
    public function it_hydrates_empty_strings_as_null()
    {
        $hydrator = new VkUserHydrator;
        $user = $hydrator->hydrate([
            'provider_id' => 123,
            'first_name' => '',
            'last_name' => '',
            'photo_max' => '',
            'sex' => '',
            'bdate' => '',
        ]);
        static::assertInstanceOf(User::class, $user);
        /** @var User $user */
        static::assertNull($user->getFirstName());
        static::assertNull($user->getLastName());
        static::assertNull($user->getSex());
        static::assertNull($user->getPhoto());
        static::assertNull($user->getBirthDate());
    }


    /**
     * @test
     */
    public function it_hydrates_unknown_sex_as_null()
    {
        $hydrator = new VkUserHydrator;
        $user = $hydrator->hydrate([
            'provider_id' => 123,
            'sex' => 'some value',
        ]);
        static::assertInstanceOf(User::class, $user);
        /** @var User $user */
        static::assertNull($user->getSex());
    }

    /**
     * @test
     */
    public function it_hydrates_males()
    {
        $hydrator = new VkUserHydrator;
        $user = $hydrator->hydrate([
            'provider_id' => 123,
            'sex' => 2,
        ]);
        static::assertInstanceOf(User::class, $user);
        /** @var User $user */
        static::assertSame('male', $user->getSex());
    }

    /**
     * @test
     */
    public function it_hydrates_females()
    {
        $hydrator = new VkUserHydrator;
        $user = $hydrator->hydrate([
            'provider_id' => 123,
            'sex' => 1,
        ]);
        static::assertInstanceOf(User::class, $user);
        /** @var User $user */
        static::assertSame('female', $user->getSex());
    }

    /**
     * @test
     */
    public function it_hydrates_non_string_birth_date_as_null()
    {
        $hydrator = new VkUserHydrator;
        $user = $hydrator->hydrate([
            'provider_id' => 123,
            'bdate' => 123,
        ]);
        static::assertInstanceOf(User::class, $user);
        /** @var User $user */
        static::assertNull($user->getBirthDate());
    }

    /**
     * @test
     */
    public function it_hydrates_birth_date_with_year()
    {
        $hydrator = new VkUserHydrator;
        $user = $hydrator->hydrate([
            'provider_id' => 123,
            'bdate' => '01.02.1999',
        ]);
        static::assertInstanceOf(User::class, $user);
        /** @var User $user */
        static::assertSame('1999-02-01', $user->getBirthDate()->format('Y-m-d'));
    }

    /**
     * @test
     */
    public function it_hydrates_birth_date_without_year()
    {
        $hydrator = new VkUserHydrator;
        $user = $hydrator->hydrate([
            'provider_id' => 123,
            'bdate' => '01.02',
        ]);
        static::assertInstanceOf(User::class, $user);
        /** @var User $user */
        static::assertSame('0000-02-01', $user->getBirthDate()->format('Y-m-d'));
    }

    /**
     * @test
     * @expectedException     \App\Exceptions\HydratorException
     * @expectedExceptionMessage A two digit day could not be found
     */
    public function it_throws_exception_if_bad_formatted_birth_date()
    {
        $hydrator = new VkUserHydrator;
        $hydrator->hydrate([
            'provider_id' => 123,
            'bdate' => 'some string',
        ]);
    }
}
