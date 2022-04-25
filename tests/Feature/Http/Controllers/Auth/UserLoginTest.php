<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserLoginTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed --class RolesSeeder');
    }

    /** @test */
    public function user_can_login()
    {
        $user = User::factory()->employee()->create();

        $response = $this->postJson('api/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $response
            ->assertOk();

        $this->assertAuthenticated();
    }

    /** @test */
    public function user_cant_login_with_wrong_password()
    {
        $user = User::factory()->create();

        $response = $this->postJson('api/login', [
            "email" => $user->email,
            "password" => 'MySecre231',
        ]);

        $response
            ->assertUnprocessable()
            ->assertJsonPath('message', 'These credentials do not match our records.');
    }

    /** @test */
    public function user_cant_login_with_wrong_email()
    {
        $response = $this->postJson('api/login', [
            "email" => $this->faker->email(),
            "password" => 'MySecre1',
        ]);

        $response
            ->assertUnprocessable()
            ->assertJsonPath('message', 'These credentials do not match our records.');
    }

    /** @test */
    public function user_cant_login_with_invalid_email()
    {
        $response = $this->postJson('api/login', [
            "email" => "invalid_email",
            "password" => 'MySecre1',
        ]);

        $response
            ->assertUnprocessable()
            ->assertInvalid('email')
            ->assertJsonValidationErrorFor('email')
            ->assertJsonValidationErrors(['email' => 'These credentials do not match our records.']);
    }

    /** @test */
    public function user_cant_login_without_email()
    {
        $response = $this->postJson('api/login', [
            "password" => 'MySecre1',
        ]);

        $response
            ->assertUnprocessable()
            ->assertInvalid('email')
            ->assertJsonValidationErrorFor('email')
            ->assertJsonPath('message', 'The email field is required.');
    }

    /** @test */
    public function user_cant_login_without_password()
    {
        $response = $this->postJson('api/login', [
            "email" => $this->faker->email(),
        ]);

        $response
            ->assertUnprocessable()
            ->assertInvalid('password')
            ->assertJsonValidationErrorFor('password')
            ->assertJsonPath('message', 'The password field is required.');
    }

}
