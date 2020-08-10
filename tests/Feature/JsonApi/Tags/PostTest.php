<?php

namespace Tests\Feature\JsonApi\Tags;

use App\User;
use Tests\TestCase;

class PostTest extends TestCase
{
    private const HEADER = [
        'Accept' => 'application/vnd.api+json',
        'Content-Type' => 'application/vnd.api+json',
    ];

    /**
     * Test a '201 Created' is returned along with the tag, when an authenticated user attempts to post a new tag using
     * a valid request format
     */
    public function testAuthenticatedUserCanPostValidTag(): void
    {
        $user = factory(User::class)->create();

        $request = [
            'data' =>  [
                'type' => 'tags',
                'attributes' => [
                    'title' => 'foo',
                    'color' => '#FF00FF',
                ]
            ]
        ];

        $tag = $this->actingAs($user, 'api')->postJson('/api/v1/tags', $request, self::HEADER)
            ->assertStatus(201)
            ->assertJsonFragment([
                'type'  => 'tags',
                'title' => $request['data']['attributes']['title'],
                'color' => $request['data']['attributes']['color'],
            ]);

        $this->assertDatabaseHas('tags', [
            'id'    => $tag['data']['id'],
            'title' => $request['data']['attributes']['title'],
            'color' => $request['data']['attributes']['color'],
        ]);
    }


    /**
     * Test a '401 Unauthorized' is returned when a guest user attempts to post a new tag
     */
    public function testGuestCannotPostTag(): void
    {
        $this->postJson('/api/v1/tags', [], self::HEADER)
            ->assertUnauthorized();
    }


    /**
     * Test a '422 Unprocessable Entity' is returned when an authenticated user attempts to post a tag using an
     * invalid request format
     *
     * @dataProvider  \Tests\Feature\JsonApi\DataProvider::tagPostPutInputValidation
     * @param string $title
     * @param string $color
     */
    public function testAuthenticatedUserCannotPostInvalidTag(string $title, string $color): void
    {
        $user = factory(User::class)->create();

        $request = [
            'data' =>  [
                'type' => 'tags',
                'attributes' => [
                    'title' => $title,
                    'color' => $color,
                ]
            ]
        ];

        $this->actingAs($user, 'api')->postJson('/api/v1/tags', $request, self::HEADER)
            ->assertStatus(422);
    }
}
