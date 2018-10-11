<?php

namespace Tests\Feature;

use App\Group;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class GroupTest extends TestCase
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
    public function it_fetches_one_group()
    {
        $group = factory(Group::class)->create();

        $response = $this->json('GET', 'api/v1/groups/1');

        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'group' => [
                    'id' => $group->id,
                    'name' => $group->name,
                ],
            ]);
    }

    /** @test */
    public function it_fetches_a_list_of_groups()
    {
        $users = factory(Group::class, $count = 100)->create();

        $response = $this->json('GET', 'api/v1/groups');

        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount($count, 'groups')
            ->assertJsonStructure([
                'groups' =>[
                    '*' => ['id', 'name'],
                ]
            ]);
    }

    /** @test */
    public function it_may_create_group()
    {
        $group = [
            'name' => 'Group Name',
        ];

        $response = $this->json('POST', 'api/v1/groups/', $group);

        $response
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'group' =>[
                    'id', 'name'
                ]
            ])
            ->assertJson([
                'message' => 'Group Created',
            ]);
    }

    /** @test */
    public function group_creating_validation_test()
    {
        $this->withExceptionHandling();

        $response = $this->json('POST', 'api/v1/groups/', []);

        $response
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                'message' => 'The given data was invalid.',
            ]);
    }

    /** @test */
    public function group_modifying_validation_test()
    {
        $this->withExceptionHandling();

        $group = factory(Group::class)->create();
        $response = $this->json('PATCH', 'api/v1/groups/'.$group->id, ['name' => '']);

        $response
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                'message' => 'The given data was invalid.',
            ]);
    }
}
