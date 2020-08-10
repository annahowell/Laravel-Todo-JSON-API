<?php

namespace Tests\Feature\Http\Controllers\AuthController;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    private const HEADER = [
        'Accept' => 'application/json'
    ];

    /**
     * Test a '201 Created' is returned along with the comment, post id and user who made the comment, when an
     * authenticated user attempts to create a new comment using a valid request format
     */
    public function testGuestCanPostValidSignup(): void
    {
        $request = [
            'displayname'           => 'validdisplayname',
            'email'                 => 'valid@email.com',
            'password'              => 'validPassw0rd!',
            'password_confirmation' => 'validPassw0rd!',
        ];

        $this->post('/api/v1/auth/signup', $request, self::HEADER)
            ->assertStatus(201)
            ->assertJson(["message" => 'User successfully created.']);

        $this->assertDatabaseHas('users', [
            'displayname' => $request['displayname'],
            'email'       => $request['email'],
        ]);
    }


    /**
     * Test a '422 Unprocessable Entity' is returned when a guest attempts to create an account using an
     * invalid request format with duplicate displayname
     */
    public function testGuestCannotPostInvalidDuplicateDisplaynameSignup(): void
    {
        factory(User::class)->create([
            'displayname' => 'duplicatedisplayname',
            'email'       => 'valid@email1.com',
            'password'    => 'password',
        ]);

        $request = [
            'displayname'           => 'duplicatedisplayname',
            'email'                 => 'valid@email2.com',
            'password'              => 'validPassw0rd!',
            'password_confirmation' => 'validPassw0rd!',
        ];

        $this->post('/api/v1/auth/signup', $request, self::HEADER)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['displayname']);

        $this->assertDatabaseMissing('users', [
            'displayname' => $request['displayname'],
            'email'       => $request['email'],
        ]);
    }


    /**
     * Test a '422 Unprocessable Entity' is returned when a guest attempts to create an account using an
     * invalid request format with duplicate email
     */
    public function testGuestCannotPostInvalidDuplicateEmailSignup(): void
    {
        factory(User::class)->create([
            'displayname' => 'validdisplayname1',
            'email'       => 'duplicate@email.com',
            'password'    => 'password',
        ]);

        $request = [
            'displayname'           => 'validdisplayname2',
            'email'                 => 'duplicate@email.com',
            'password'              => 'validPassw0rd!',
            'password_confirmation' => 'validPassw0rd!',
        ];

        $this->post('/api/v1/auth/signup', $request, self::HEADER)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['email']);

        $this->assertDatabaseMissing('users', [
            'displayname' => $request['displayname'],
            'email'       => $request['email'],
        ]);
    }


    /**
     * Test a '422 Unprocessable Entity' is returned when a guest attempts to create an account using an
     * invalid request format with strings
     *
     * @param string $input
     * @param string $inputValue
     * @dataProvider  \Tests\Feature\Http\Controllers\DataProvider::userPostStringSignUpInputValidation

     */
    public function testGuestCannotPostInvalidStringSignup(string $input, string $inputValue): void
    {
        $this->post('/api/v1/auth/signup', [$input => $inputValue], self::HEADER)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['displayname', 'email', 'password']);
    }


    /**
     * Test a '422 Unprocessable Entity' is returned when a guest attempts to create an account using an
     * invalid request format with integers
     *
     * @param string $input
     * @param int $inputValue
     * @dataProvider  \Tests\Feature\Http\Controllers\DataProvider::userPostIntSignUpInputValidation
     */
    public function testGuestCannotPostInvalidNonStringSignup(string $input, int $inputValue): void
    {
        $this->post('/api/v1/auth/signup', [$input => $inputValue], self::HEADER)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['displayname', 'email', 'password']);
    }


    /**
     * Test a '200 Ok' is returned along with access token and token details when a signed up user attempts to login
     * with valid credentials
     */
    public function testSignedUpUserCanPostValidLogin(): void
    {
        \Artisan::call('passport:install');

        $user = factory(User::class)->create();

        $request = [
            'email'    => $user['email'],
            'password' => 'Password123!', // default factory password
        ];

        $this->post('/api/v1/auth/login', $request, self::HEADER)
            ->assertStatus(200)
            ->assertJsonStructure([
                'access_token',
                'token_type',
                'expires_at',
            ]);
    }


    /**
     * Test a '422 Unprocessable Entity' is returned when a signed up user attempts to login with invalid credentials
     */
    public function testSignedUpUserCannotPostInvalidLoginPassword(): void
    {
        $user = factory(User::class)->create();

        $request = [
            'email'    => $user['email'],
            'password' => 'passw0rdPassesValidationButDoesntMatch!',
        ];

        $this->post('/api/v1/auth/login', $request, self::HEADER)
            ->assertUnauthorized();
    }


    /**
     * Test a '422 Unprocessable Entity' is returned when a guest attempts to login to an account using an
     * invalid request format
     *
     * @param string $input
     * @param string $inputValue
     * @dataProvider  \Tests\Feature\Http\Controllers\DataProvider::userPostLoginInputValidation
     */
    public function testSignedUpUserCannotPostInvalidLogin(string $input, string $inputValue): void
    {
        $this->post('/api/v1/auth/login', [$input => $inputValue], self::HEADER)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['email', 'password']);
    }
}
