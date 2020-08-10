<?php

namespace Tests\Feature\JsonApi\Tasks;

use App\Task;
use App\TaskTag;
use App\User;
use App\Tag;
use Tests\TestCase;

class GetTest extends TestCase
{
    private const HEADER = [
        'Accept' => 'application/vnd.api+json',
        'Content-Type' => 'application/vnd.api+json',
    ];

    /**
     * Test a '200 Ok' is returned along with the tasks when an authenticated user attempts to get all existing tasks
     */
    public function testAuthenticatedUserCanGetAllExistingTasks(): void
    {
        $user = factory(User::class)->create();
        $task = factory(Task::class)->create();

        $this->actingAs($user, 'api')->get('/api/v1/tasks', self::HEADER)
            ->assertStatus(200)
            ->assertJsonFragment([
                'type'  => 'tasks',
                'id'    => (string) $task['id'],
                'body' => $task['body'],
            ]);
    }


    /**
     * Test a '200 Ok' is returned along with the task when an authenticated user attempts to get an existing task
     */
    public function testAuthenticatedUserCanGetExistingTask(): void
    {
        $user = factory(User::class)->create();
        $task = factory(Task::class)->create();

        $this->actingAs($user, 'api')->get('/api/v1/tasks/' . $task['id'], self::HEADER)
            ->assertStatus(200)
            ->assertJsonFragment([
                'type'  => 'tasks',
                'id'    => (string) $task['id'],
                'body' => $task['body'],
            ]);
    }


    /**
     * Test a '404 Not Found' is returned when an authenticated user attempts to get a nonexistent task
     */
    public function testAuthenticatedUserCannotGetNonexistentTask(): void
    {
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')->get('/api/v1/tasks/1', self::HEADER)
            ->assertNotFound();
    }


    /**
     * Test a '200 Ok' is returned along with the task and tags related to it, when an authenticated user attempts to
     * get an existing task with related tags
     */
    public function testAuthenticatedUserCanGetExistingTaskWithTags(): void
    {
        $user = factory(User::class)->create();
        $task = factory(Task::class)->create();
        $tag = factory(Tag::class)->create();
        factory(TaskTag::class)->create();

        $this->actingAs($user, 'api')->get('/api/v1/tasks/' . $task['id'] . '?include=tags', self::HEADER)
            ->assertStatus(200)
            ->assertJsonFragment([
                'type' => 'tasks',
                'id'   => (string)$task['id'],
                'body' => $task['body'],
            ])
            ->assertJsonFragment([
                'type'  => 'tasks',
                'id'    => (string) $tag['id'],
                'title' => $tag['title'],
                'color' => $tag['color'],
            ]);
    }
    

    /**
     * Test a '401 Unauthorized' is returned when a guest user attempts to get a task
     */
    public function testGuestCannotGetTasks(): void
    {
        $this->get('/api/v1/tasks', self::HEADER)
            ->assertUnauthorized();
    }
}
