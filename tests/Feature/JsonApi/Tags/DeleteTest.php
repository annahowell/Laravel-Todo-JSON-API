<?php

namespace Tests\Feature\JsonApi\Tags;

use App\Tag;
use App\User;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    private const HEADER = [
        'Accept' => 'application/vnd.api+json',
        'Content-Type' => 'application/vnd.api+json',
    ];

    /**
     * Test a '204 No Content' is returned when an authenticated user attempts to delete their own existing tag
     */
    public function testAuthenticatedUserCanDeleteTheirOwnExistingTag(): void
    {
        $user = factory(User::class)->create();
        $tag = factory(Tag::class)->create();

        $this->actingAs($user, 'api')->delete('/api/v1/tags/' . $tag['id'], self::HEADER)
            ->assertNoContent(204);

        $this->assertDatabaseMissing('tags', [
            'id' => $tag['id'],
        ]);
    }


    /**
     * Test a '401 Unauthorized' is returned when a guest user attempts to delete an existing tag
     */
    public function testGuestCannotDeleteExistingTag(): void
    {
        factory(User::class)->create();
        $tag = factory(Tag::class)->create();

        $this->delete('/api/v1/tags/' . $tag['id'], [], self::HEADER)
            ->assertUnauthorized();

        $this->assertDatabaseHas('tags', [
            'id' => $tag['id'],
        ]);
    }


    /**
     * Test a '403 Forbidden' is returned when an authenticated user attempts to delete an existing tag made by another
     * user
     */
    public function testAuthenticatedUserCannotDeleteAnotherUsersExistingTag(): void
    {
        factory(User::class)->create();
        $tag = factory(Tag::class)->create();
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')->delete('/api/v1/tags/' . $tag['id'], self::HEADER)
            ->assertForbidden();

        $this->assertDatabaseHas('tags', [
            'id' => $tag['id'],
        ]);
    }


    /**
     * Test a '404 Not Found' is returned when an authenticated user attempts to delete a nonexistent tag
     */
    public function testAuthenticatedUserCannotDeleteNonexistentTag(): void
    {
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')->delete('/api/v1/tags/1', [], self::HEADER)
            ->assertNotFound();
    }
}
