<?php

namespace Tests\Feature;

use App\Group;
use App\User;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class UserTest extends TestCase
{

    /**
     * [setUp description]
     */
    protected function setUp()
    {
        parent::setUp();

        $this->signIn();
    }

    /** @test */
    public function it_fetches_one_user()
    {
        $user = factory(User::class)->create();

        $response = $this->json('GET', 'api/v1/users/' . $user->id);

        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'user' => [
                    'id' => $user->id,
                    'last_name' => $user->last_name,
                    'first_name' => $user->first_name,
                    'email' => $user->email,
                    'state' => $user->state,
                ],
            ]);
    }

    /** @test */
    public function it_fetches_a_list_of_users()
    {
        $users = factory(User::class, $count = 50)->create();

        $response = $this->json('GET', 'api/v1/users');

        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount($count+1, 'users') //+actingAs user
            ->assertJsonStructure([
                'users' =>[
                    '*' => ['id', 'last_name', 'first_name', 'email', 'state', 'created_at'],
                ]
            ]);
    }

    /** @test */
    public function it_may_create_user()
    {
        $groups = factory(Group::class, 2)->create();

        $user = [
            'first_name' => 'Mark',
            'last_name' => 'Watney',
            'email' => 'watney@example.com',
            'state' => 1,
            'password' => 'password',
            'password_confirmation' => 'password',
            'groups_id' => [1, 2]
        ];

        $response = $this->json('POST', 'api/v1/users/', $user);

        $response
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'user' =>[
                    'id', 'last_name', 'first_name', 'email', 'state', 'created_at',
                    'groups' =>[
                        '*' => ['id', 'name'],
                    ]
                ]
            ])
            ->assertJson([
                'message' => 'User Created',
            ])
            ->assertJsonCount(2, 'user.groups');
    }

    /** @test */
    public function it_may_modify_user()
    {
        $user = factory(User::class)->create();

        $response = $this->json('GET', 'api/v1/users/' . $user->id);
        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'user' => [
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                ],
            ]);

        $user->first_name = 'New First Name';
        $user->last_name = 'New last Name';
        $user->email = 'new_email@example.com';

        $response = $this->json('PATCH', 'api/v1/users/'. $user->id, $user->toArray());
        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'user' => [
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                ],
            ]);
    }
}
