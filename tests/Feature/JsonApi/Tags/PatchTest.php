<?php

namespace Tests\Feature\JsonApi\Tags;

use App\Tag;
use App\User;
use Tests\TestCase;

class PatchTest extends TestCase
{
    private const HEADER = [
        'Accept' => 'application/vnd.api+json',
        'Content-Type' => 'application/vnd.api+json',
    ];

    /**
     * Test a '200 Ok' is returned along with the tag, when an authenticated user attempts to patch an existing tag
     * using a valid request format
     */
    public function testAuthenticatedUserCanPatchValidTag(): void
    {
        $user = factory(User::class)->create();
        $tag = factory(Tag::class)->create();

        $request = [
            'data' =>  [
                'type' => 'tags',
                'id'   => (string) $tag['id'],
                'attributes' => [
                    'title' => 'foo',
                    'color' => '#FF00FF',
                ]
            ]
        ];

        $this->actingAs($user, 'api')->patchJson('/api/v1/tags/' . $tag['id'], $request, self::HEADER)
            ->assertStatus(200)
            ->assertJsonFragment([
                'type'  => 'tags',
                'title' => $request['data']['attributes']['title'],
                'color' => $request['data']['attributes']['color'],
            ]);

        $this->assertDatabaseHas('tags', [
            'id'    => $tag['id'],
            'title' => $request['data']['attributes']['title'],
            'color' => $request['data']['attributes']['color'],
        ]);
    }


    /**
     * Test a '401 Unauthorized' is returned when a guest user attempts to patch an existing tag
     */
    public function testGuestCannotPatchTag(): void
    {
        factory(User::class)->create();
        $tag = factory(Tag::class)->create();

        $this->patchJson('/api/v1/tags/' . $tag['id'], [], self::HEADER)
            ->assertUnauthorized();
    }


    /**
     * Test a '403 Forbidden' is returned when an authenticated user attempts to patch an existing tag made by another
     * user
     */
    public function testAuthenticatedUserCannotPatchAnotherUsersExistingTag(): void
    {
        factory(User::class)->create();
        $tag = factory(Tag::class)->create();
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')->patchJson('/api/v1/tags/' . $tag['id'], [], self::HEADER)
            ->assertForbidden();

        $this->assertDatabaseHas('tags', [
            'id'    => $tag['id'],
            'title' => $tag['title'],
            'color' => $tag['color'],
        ]);
    }
    

    /**
     * Test a '422 Unprocessable Entity' is returned when an authenticated user attempts to patch a tag using an
     * invalid request format
     *
     * @dataProvider  \Tests\Feature\JsonApi\DataProvider::tagPostPutInputValidation
     * @param string $title
     * @param string $color
     */
    public function testAuthenticatedUserCannotPatchInvalidTag(string $title, string $color): void
    {
        $user = factory(User::class)->create();
        $tag = factory(Tag::class)->create();

        $request = [
            'data' =>  [
                'type' => 'tags',
                'id' => (string) $tag['id'],
                'attributes' => [
                    'title' => $title,
                    'color' => $color,
                ]
            ]
        ];

        $this->actingAs($user, 'api')->patchJson('/api/v1/tags/' . $tag['id'], $request, self::HEADER)
            ->assertStatus(422);
    }
}
