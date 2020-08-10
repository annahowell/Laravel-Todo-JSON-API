<?php

namespace Tests\Feature\JsonApi\Tags;

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
     * Test a '200 Ok' is returned along with the tags when an authenticated user attempts to get all existing tags
     */
    public function testAuthenticatedUserCanGetAllExistingTags(): void
    {
        $user = factory(User::class)->create();
        $tag = factory(Tag::class)->create();

        $this->actingAs($user, 'api')->get('/api/v1/tags', self::HEADER)
            ->assertStatus(200)
            ->assertJsonFragment([
                'type'  => 'tags',
                'id'    => (string) $tag['id'],
                'title' => $tag['title'],
                'color' => $tag['color'],
            ]);
    }


    /**
     * Test a '200 Ok' is returned along with the tag when an authenticated user attempts to get an existing tag
     */
    public function testAuthenticatedUserCanGetExistingTag(): void
    {
        $user = factory(User::class)->create();
        $tag = factory(Tag::class)->create();

        $this->actingAs($user, 'api')->get('/api/v1/tags/' . $tag['id'], self::HEADER)
            ->assertStatus(200)
            ->assertJsonFragment([
                'type'  => 'tags',
                'id'    => (string) $tag['id'],
                'title' => $tag['title'],
                'color' => $tag['color'],
            ]);
    }


    /**
     * Test a '404 Not Found' is returned when an authenticated user attempts to get a nonexistent tag
     */
    public function testAuthenticatedUserCannotGetNonexistentTag(): void
    {
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')->get('/api/v1/tags/1', self::HEADER)
            ->assertNotFound();
    }


    /**
     * Test a '200 Ok' is returned along with the tag and tasks related to it, when an authenticated user attempts to
     * get an existing tag with related tasks
     */
    public function testAuthenticatedUserCanGetExistingTagWithTasks(): void
    {
        $user = factory(User::class)->create();
        $tag = factory(Tag::class)->create();
        $task = factory(task::class)->create();
        factory(TaskTag::class)->create();

        $this->actingAs($user, 'api')->get('/api/v1/tags/' . $tag['id'] . '?include=tasks', self::HEADER)
            ->assertStatus(200)
            ->assertJsonFragment([
                'type'  => 'tags',
                'id'    => (string) $tag['id'],
                'title' => $tag['title'],
                'color' => $tag['color'],
            ])
            ->assertJsonFragment([
                'type' => 'tasks',
                'id'   => (string)$task['id'],
                'body' => $task['body'],
            ]);
    }


    /**
     * Test a '401 Unauthorized' is returned when a guest user attempts to get a tag
     */
    public function testGuestCannotGetTags(): void
    {
        $this->get('/api/v1/tags', self::HEADER)
            ->assertUnauthorized();
    }
}
