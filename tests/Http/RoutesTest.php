<?php declare(strict_types = 1);

namespace App\Http;

use App\Resources\User;
use App\TestCase;

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

        $user = new User('provider', 123);
        $user->setId(12334567);
        $this->be($user);
        static::assertContains('\'id\' => 12334567', $this->get('/')->response->content());
    }

    /**
     * @test
     */
    public function it_handles_get_app()
    {
        $this->withoutMiddleware();

        $user = new User('provider', 123);
        $user->setId(12334567);
        $this->be($user);
        static::assertContains('\'id\' => 12334567', $this->get('/app/some_provider')->response->content());
    }
}
