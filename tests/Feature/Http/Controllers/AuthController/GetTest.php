<?php

namespace Tests\Feature\Http\Controllers\AuthController;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetTest extends TestCase
{
    use RefreshDatabase;

    private const HEADER = [
        'Accept' => 'application/json'
    ];


    /**
     * Test a '200 Ok' is returned along with the user details when an authenticated user attempts to get their own
     * details
     */
    public function testAuthenticatedUserCanGetExistingOwnUserDetails(): void
    {
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')->get('/api/v1/auth/user', self::HEADER)
            ->assertStatus(200)
            ->assertJson([
                'id'          => $user['id'],
                'displayname' => $user['displayname'],
            ]);
    }


    /**
     * Test a '401 Unauthorized' is returned when a guest attempts to get their own user details
     */
    public function testGuestCannotGetExistingOwnUserDetails(): void
    {
        $this->get('/api/v1/auth/user', self::HEADER)
            ->assertUnauthorized();
    }


    /**
     * Test a '200 Ok' is returned when an authenticated user attempts to logout
     */
    public function testAuthenticatedUserCanLogout(): void
    {
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')->get('/api/v1/auth/logout', self::HEADER)
            ->assertStatus(200)
            ->assertJson(["message" => 'Successfully logged out.']);
    }
    

    /**
     * Test a '401 Unauthorized' is returned when a guest attempts to logout
     */
    public function testGuestCannotGetLogout(): void
    {
        $this->get('/api/v1/auth/logout', self::HEADER)
            ->assertUnauthorized();
    }
}
