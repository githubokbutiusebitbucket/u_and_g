<?php

namespace Tests\Feature;

use App\Group;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class AuthTest extends TestCase
{
    /**
     * [setUp description]
     */
    protected function setUp()
    {
        parent::setUp();

        $this->withExceptionHandling();
    }

    /** @test */
    public function unauthenticated_may_not_see_data()
    {
        $response = $this->get('/api/v1/users');
        $response->assertStatus(302);

        $response = $this->get('/api/v1/groups');
        $response->assertStatus(302);

        $group = factory(Group::class)->create();
        $response = $this->get('/api/v1/groups/' . $group->id);
        $response->assertStatus(302);

        $user = factory(User::class)->create();
        $response = $this->get('/api/v1/users/' . $user->id);
        $response->assertStatus(302);

        $user = factory(User::class)->make();
        $response = $this->post('/api/v1/users/', $user->toArray());
        $response->assertStatus(302);

        $group = factory(Group::class)->make();
        $response = $this->post('/api/v1/groups/', $group->toArray());
        $response->assertStatus(302);
    }
}
