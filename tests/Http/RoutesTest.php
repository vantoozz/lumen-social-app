<?php

namespace App\Http;


use App\Resources\User;
use App\TestCase;
use Illuminate\Contracts\Auth\Guard;

class RoutesTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();
        $this->artisan('migrate:refresh');
    }

    /**
     * @test
     */
    public function it_handles_root()
    {
        $this->withoutMiddleware();

        $user = new User;
        $user->setId(12334567);
        $this->be($user);
        $this->visit('/')->see('\'id\' => 12334567');
    }

    /**
     * @test
     */
    public function it_handles_get_app()
    {
        $this->withoutMiddleware();

        $user = new User;
        $user->setId(12334567);
        $this->be($user);
        $this->visit('/app/some_provider')->see('\'id\' => 12334567');
    }

}