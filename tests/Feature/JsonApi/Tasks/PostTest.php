<?php

namespace Tests\Feature\JsonApi\Tasks;

use App\Tag;
use App\Task;
use App\User;
use Tests\TestCase;

class PostTest extends TestCase
{
    private const HEADER = [
        'Accept'       => 'application/vnd.api+json',
        'Content-Type' => 'application/vnd.api+json',
    ];

    /**
     * Test a '200 Ok' is returned along with the task, when an authenticated user attempts to post a new task using a
     * valid request format
     */
    public function testAuthenticatedUserCanPostValidTask(): void
    {
        $user = factory(User::class)->create();

        $request = [
            'data' =>  [
                'type' => 'tasks',
                'attributes' => [
                    'body' => 'foo',
                ]
            ]
        ];

        $task = $this->actingAs($user, 'api')->postJson('/api/v1/tasks', $request, self::HEADER)
            ->assertStatus(201)
            ->assertJsonFragment([
                'type'  => 'tasks',
                'body'  => $request['data']['attributes']['body'],
            ]);

        $this->assertDatabaseHas('tasks', [
            'id'   => $task['data']['id'],
            'body' => $request['data']['attributes']['body'],
        ]);
    }


    /**
     * Test a '200 Ok' is returned along with the task and tags, when an authenticated user attempts to post a new task
     * with tags using a valid request format
     */
    public function testAuthenticatedUserCanPostValidTaskWithTags(): void
    {
        $user = factory(User::class)->create();
        $tagOne = factory(Tag::class)->create();
        $tagTwo = factory(Tag::class)->create();

        $request = [
            'data' =>  [
                'type' => 'tasks',
                'attributes' => [
                    'body' => 'foo',
                ],
                'relationships' => [
                    'tags' => [
                        'data' => [
                            [
                                'type' => 'tags',
                                'id'   => (string) $tagOne['id']
                            ],
                            [
                                'type' => 'tags',
                                'id'   => (string) $tagTwo['id']
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $task = $this->actingAs($user, 'api')->postJson('/api/v1/tasks', $request, self::HEADER)
            ->assertStatus(201)
            ->assertJsonFragment([
                'type'  => 'tasks',
                'body' => $request['data']['attributes']['body'],
            ])
            ->assertJsonFragment([
                'type'  => 'tags',
                'title' => $tagOne['title'],
                'color' => $tagOne['color'],
            ])
            ->assertJsonFragment([
                'type'  => 'tags',
                'title' => $tagTwo['title'],
                'color' => $tagTwo['color'],
            ]);


        $this->assertDatabaseHas('tasks', [
            'id'   => $task['data']['id'],
            'body' => $request['data']['attributes']['body'],
        ]);
    }


    /**
     * Test a '401 Unauthorized' is returned when a guest user attempts to post a new task
     */
    public function testGuestCannotPostTask(): void
    {
        $this->postJson('/api/v1/tasks', [], self::HEADER)
            ->assertUnauthorized();
    }


    /**
     * Test a '422 Unprocessable Entity' is returned when an authenticated user attempts to post a task using an
     * invalid request format
     */
    public function testAuthenticatedUserCannotPostInvalidTask(): void
    {
        $user = factory(User::class)->create();
        $tag = factory(Tag::class)->create();

        $request = [
            'data' =>  [
                'type' => 'tasks',
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

        $this->actingAs($user, 'api')->postJson('/api/v1/tasks', $request, self::HEADER)
            ->assertStatus(422);
    }

    
    /**
     * Test a '404 Not Found' is returned when an authenticated user attempts to post a task using a valid request
     * format but includes a tag relationship that doesn't exist
     */
    public function testAuthenticatedUserCannotPOstValidTaskWithNonexistentTag(): void
    {
        $user = factory(User::class)->create();
        factory(Task::class)->create();
        factory(Tag::class)->create();

        $request = [
            'data' =>  [
                'type' => 'tasks',
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

        $this->actingAs($user, 'api')->postJson('/api/v1/tasks', $request, self::HEADER)
            ->assertStatus(404);
    }
}
