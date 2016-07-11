<?php

namespace App\Social\Provider;

use App\Hydrators\User\VkUserHydrator;
use App\Resources\User;
use App\TestCase;
use Novanova\VK\VK as VkDriver;

class VKTest extends TestCase
{
    /**
     * @test
     */
    public function it_returns_frame_user()
    {
        $driver = $this->getMockBuilder(VkDriver::class)->disableOriginalConstructor()->getMock();
        $hydrator = $this->createMock(VkUserHydrator::class);
        $hydratedUser = new User('some provider', 123);

        $hydratedUser->populate(['first_name' => 'some name']);

        $driver
            ->expects(static::once())
            ->method('calculateAuthKey')
            ->with('123')
            ->willReturn('auth_key_string');

        $hydrator
            ->expects(static::once())
            ->method('hydrate')
            ->with([
                'provider_id' => '123'
            ])
            ->willReturn($hydratedUser);

        /** @var \Novanova\VK\VK $driver */
        /** @var VkUserHydrator $hydrator */
        $provider = new VK($driver, $hydrator);
        $user = $provider->getFrameUser([
            'viewer_id' => '123',
            'auth_key' => 'auth_key_string'
        ]);

        static::assertSame($hydratedUser, $user);
    }

    /**
     * @test
     */
    public function it_handles_first_api_call_result()
    {
        $driver = $this->getMockBuilder(VkDriver::class)->disableOriginalConstructor()->getMock();
        $hydrator = $this->createMock(VkUserHydrator::class);
        $hydratedUser = new User('some provider', 123);

        $hydratedUser->populate(['first_name' => 'some name']);

        $driver
            ->expects(static::once())
            ->method('calculateAuthKey')
            ->with('123')
            ->willReturn('auth_key_string');

        $hydrator
            ->expects(static::once())
            ->method('hydrate')
            ->with([
                'provider_id' => '123',
                'last_name' => 'some name',
                'sex' => 2
            ])
            ->willReturn($hydratedUser);

        /** @var \Novanova\VK\VK $driver */
        /** @var VkUserHydrator $hydrator */
        $provider = new VK($driver, $hydrator);
        $user = $provider->getFrameUser([
            'viewer_id' => '123',
            'auth_key' => 'auth_key_string',
            'api_result' => '{"response":[{"last_name":"some name","sex":2}]}'
        ]);

        static::assertSame($hydratedUser, $user);
    }

    /**
     * @test
     */
    public function it_handles_malformed_first_api_call_result()
    {
        $driver = $this->getMockBuilder(VkDriver::class)->disableOriginalConstructor()->getMock();
        $hydrator = $this->createMock(VkUserHydrator::class);
        $hydratedUser = new User('some provider', 123);

        $hydratedUser->populate(['first_name' => 'some name']);

        $driver
            ->expects(static::once())
            ->method('calculateAuthKey')
            ->with('123')
            ->willReturn('auth_key_string');

        $hydrator
            ->expects(static::once())
            ->method('hydrate')
            ->with([
                'provider_id' => '123'
            ])
            ->willReturn($hydratedUser);

        /** @var \Novanova\VK\VK $driver */
        /** @var VkUserHydrator $hydrator */
        $provider = new VK($driver, $hydrator);
        $user = $provider->getFrameUser([
            'viewer_id' => '123',
            'auth_key' => 'auth_key_string',
            'api_result' => '{"response'
        ]);

        static::assertSame($hydratedUser, $user);
    }

    /**
     * @test
     */
    public function it_handles_empty_first_api_call_result()
    {
        $driver = $this->getMockBuilder(VkDriver::class)->disableOriginalConstructor()->getMock();
        $hydrator = $this->createMock(VkUserHydrator::class);
        $hydratedUser = new User('some provider', 123);

        $hydratedUser->populate(['first_name' => 'some name']);

        $driver
            ->expects(static::once())
            ->method('calculateAuthKey')
            ->with('123')
            ->willReturn('auth_key_string');

        $hydrator
            ->expects(static::once())
            ->method('hydrate')
            ->with([
                'provider_id' => '123'
            ])
            ->willReturn($hydratedUser);

        /** @var \Novanova\VK\VK $driver */
        /** @var VkUserHydrator $hydrator */
        $provider = new VK($driver, $hydrator);
        $user = $provider->getFrameUser([
            'viewer_id' => '123',
            'auth_key' => 'auth_key_string',
            'api_result' => '{"response":[]}'
        ]);

        static::assertSame($hydratedUser, $user);
    }

    /**
     * @test
     * @expectedException     \App\Exceptions\SocialException
     * @expectedExceptionMessage No viewer_id field
     */
    public function it_throws_exception_if_no_viewer_id()
    {
        $driver = $this->getMockBuilder(VkDriver::class)->disableOriginalConstructor()->getMock();
        $hydrator = $this->createMock(VkUserHydrator::class);

        $driver
            ->expects(static::never())
            ->method('calculateAuthKey');

        $hydrator
            ->expects(static::never())
            ->method('hydrate');

        /** @var \Novanova\VK\VK $driver */
        /** @var VkUserHydrator $hydrator */
        $provider = new VK($driver, $hydrator);
        $provider->getFrameUser([
            'some_field' => '123',
            'auth_key' => 'auth_key_string',
            'api_result' => '{"response":[]}'
        ]);
    }

    /**
     * @test
     * @expectedException     \App\Exceptions\SocialException
     * @expectedExceptionMessage No auth_key field
     */
    public function it_throws_exception_if_no_auth_key()
    {
        $driver = $this->getMockBuilder(VkDriver::class)->disableOriginalConstructor()->getMock();
        $hydrator = $this->createMock(VkUserHydrator::class);

        $driver
            ->expects(static::never())
            ->method('calculateAuthKey');

        $hydrator
            ->expects(static::never())
            ->method('hydrate');

        /** @var \Novanova\VK\VK $driver */
        /** @var VkUserHydrator $hydrator */
        $provider = new VK($driver, $hydrator);
        $provider->getFrameUser([
            'viewer_id' => '123',
            'some_field' => 'auth_key_string',
            'api_result' => '{"response":[]}'
        ]);
    }

    /**
     * @test
     * @expectedException     \App\Exceptions\SocialException
     * @expectedExceptionMessage Bad auth key
     */
    public function it_throws_exception_if_bad_auth_key()
    {
        $driver = $this->getMockBuilder(VkDriver::class)->disableOriginalConstructor()->getMock();
        $hydrator = $this->createMock(VkUserHydrator::class);

        $driver
            ->expects(static::once())
            ->method('calculateAuthKey')
            ->with('123')
            ->willReturn('auth_key_string');

        $hydrator
            ->expects(static::never())
            ->method('hydrate');

        /** @var \Novanova\VK\VK $driver */
        /** @var VkUserHydrator $hydrator */
        $provider = new VK($driver, $hydrator);
        $provider->getFrameUser([
            'viewer_id' => '123',
            'auth_key' => 'bad_auth_key',
            'api_result' => '{"response":[]}'
        ]);
    }

    /**
     * @test
     */
    public function it_gets_user_by_provider_id()
    {
        $driver = $this->getMockBuilder(VkDriver::class)->disableOriginalConstructor()->getMock();
        $hydrator = $this->createMock(VkUserHydrator::class);
        $hydratedUser = new User('some provider', 123);

        $hydratedUser->populate(['first_name' => 'some name']);

        $driver
            ->expects(static::once())
            ->method('no_auth_api')
            ->with(
                'users.get',
                [
                    'user_id' => 123,
                    'fields' => 'uid,first_name,last_name,sex,photo_max,bdate'
                ]
            )
            ->willReturn([
                [
                    'last_name' => 'some name',
                    'sex' => 2
                ]
            ]);

        $hydrator
            ->expects(static::once())
            ->method('hydrate')
            ->with([
                'provider_id' => 123,
                'last_name' => 'some name',
                'sex' => 2
            ])
            ->willReturn($hydratedUser);

        /** @var \Novanova\VK\VK $driver */
        /** @var VkUserHydrator $hydrator */
        $provider = new VK($driver, $hydrator);
        $user = $provider->getUserByProviderId(123);
        static::assertSame($hydratedUser, $user);
    }
}
