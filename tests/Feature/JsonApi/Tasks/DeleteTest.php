<?php

namespace Tests\Feature\JsonApi\Tasks;

use App\Task;
use App\User;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    private const HEADER = [
        'Accept' => 'application/vnd.api+json',
        'Content-Type' => 'application/vnd.api+json',
    ];

    /**
     * Test a '204 No Content' is returned when an authenticated user attempts to delete their own existing task
     */
    public function testAuthenticatedUserCanDeleteTheirOwnExistingTask(): void
    {
        $user = factory(User::class)->create();
        $task = factory(Task::class)->create();

        $this->actingAs($user, 'api')->delete('/api/v1/tasks/' . $task['id'], self::HEADER)
            ->assertNoContent(204);

        $this->assertDatabaseMissing('tasks', [
            'id' => $task['id'],
        ]);
    }


    /**
     * Test a '401 Unauthorized' is returned when a guest user attempts to delete an existing task
     */
    public function testGuestCannotDeleteExistingTask(): void
    {
        factory(User::class)->create();
        $task = factory(Task::class)->create();

        $this->delete('/api/v1/tasks/' . $task['id'], [], self::HEADER)
            ->assertUnauthorized();

        $this->assertDatabaseHas('tasks', [
            'id' => $task['id'],
        ]);
    }


    /**
     * Test a '403 Forbidden' is returned when an authenticated user attempts to delete an existing task made by another
     * user
     */
    public function testAuthenticatedUserCannotDeleteAnotherUsersExistingTask(): void
    {
        factory(User::class)->create();
        $task = factory(Task::class)->create();
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')->delete('/api/v1/tasks/' . $task['id'], self::HEADER)
            ->assertForbidden();

        $this->assertDatabaseHas('tasks', [
            'id' => $task['id'],
        ]);
    }


    /**
     * Test a '404 Not Found' is returned when an authenticated user attempts to delete a nonexistent task
     */
    public function testAuthenticatedUserCannotDeleteNonexistentTask(): void
    {
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')->delete('/api/v1/tasks/1', [], self::HEADER)
            ->assertNotFound();
    }
}
