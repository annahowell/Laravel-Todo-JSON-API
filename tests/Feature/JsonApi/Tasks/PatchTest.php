<?php

namespace Tests\Feature\JsonApi\Tasks;

use App\Tag;
use App\Task;
use App\User;
use Tests\TestCase;

class PatchTest extends TestCase
{
    private const HEADER = [
        'Accept' => 'application/vnd.api+json',
        'Content-Type' => 'application/vnd.api+json',
    ];

    /**
     * Test a '200 Ok' is returned along with the task, when an authenticated user attempts to patch an existing task
     * using a valid request format
     */
    public function testAuthenticatedUserCanPatchValidTask(): void
    {
        $user = factory(User::class)->create();
        $task = factory(Task::class)->create();

        $request = [
            'data' =>  [
                'type' => 'tasks',
                'id'   => (string) $task['id'],
                'attributes' => [
                    'body' => 'foo',
                ]
            ]
        ];

        $this->actingAs($user, 'api')->patchJson('/api/v1/tasks/' . $task['id'], $request, self::HEADER)
            ->assertStatus(200)
            ->assertJsonFragment([
                'type'  => 'tasks',
                'body' => $request['data']['attributes']['body'],
            ]);

        $this->assertDatabaseHas('tasks', [
            'id'    => $task['id'],
            'body' => $request['data']['attributes']['body'],
        ]);
    }


    /**
     * Test a '401 Unauthorized' is returned when a guest user attempts to patch an existing task
     */
    public function testGuestCannotPatchTask(): void
    {
        factory(User::class)->create();
        $task = factory(Task::class)->create();

        $this->patchJson('/api/v1/tasks/' . $task['id'], [], self::HEADER)
            ->assertUnauthorized();
    }


    /**
     * Test a '422 Unprocessable Entity' is returned when an authenticated user attempts to patch a task using an
     * invalid request format
     */
    public function testAuthenticatedUserCannotPatchInvalidTask(): void
    {
        $user = factory(User::class)->create();
        $task = factory(Task::class)->create();
        $tag = factory(Tag::class)->create();

        $request = [
            'data' =>  [
                'type' => 'tasks',
                'id' => (string) $task['id'],
                'attributes' => [
                    'body' => '', // missing
                ],
                'relationships' => [
                    'tags' => [
                        'data' => [
                            [
                                'type' => 'tags',
                                'id'   => (string) $tag['id']
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $this->actingAs($user, 'api')->patchJson('/api/v1/tasks/' . $task['id'], $request, self::HEADER)
            ->assertStatus(422);
    }
    

    /**
     * Test a '4Z0 Not Found' is returned when an authenticated user attempts to patch a task using a valid request
     * format but includes a tag relationship that doesn't exist
     */
    public function testAuthenticatedUserCannotPatchValidTaskWithNonexistentTag(): void
    {
        $user = factory(User::class)->create();
        $task = factory(Task::class)->create();
        factory(Tag::class)->create();

        $request = [
            'data' =>  [
                'type' => 'tasks',
                'id' => (string) $task['id'],
                'attributes' => [
                    'body' => 'valid body',
                ],
                'relationships' => [
                    'tags' => [
                        'data' => [
                            [
                                'type' => 'tags',
                                'id'   => '999' // Nonexistent
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $this->actingAs($user, 'api')->patchJson('/api/v1/tasks/' . $task['id'], $request, self::HEADER)
            ->assertStatus(404);
    }
}
