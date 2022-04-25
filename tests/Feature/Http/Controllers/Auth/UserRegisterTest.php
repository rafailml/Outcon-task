<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Jobs\ProcessCoworkersEmails;
use App\Models\User;
use App\Notifications\EmailToCoworkers;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class UserRegisterTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function user_can_register()
    {
        Queue::fake();

        $user = User::factory()->make();
        $response = $this->postJson('api/register',
            [
                'name' => $user->name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'password' => 'MySecre1',
                'password_confirmation' => 'MySecre1',
            ]);

        $response
            ->assertCreated();

        $this->assertDatabaseHas('users', ['email' => $user->email]);

        Queue::assertPushed(ProcessCoworkersEmails::class, 1);
    }

    /** @test */
    public function user_cant_register_without_name()
    {
        $response = $this->postJson('api/register', [
            "email" => $this->faker->email,
            'last_name' => $this->faker->lastName(),
            "password" => 'Pa$$w0rd!',
            "password_confirmation" => 'Pa$$w0rd!',
        ]);

        $response
            ->assertUnprocessable()
            ->assertInvalid('name')
            ->assertJsonValidationErrorFor('name')
            ->assertJsonValidationErrors(['name' => 'The name field is required.']);
    }

    /** @test */
    public function user_cant_register_without_last_name()
    {
        $response = $this->postJson('api/register', [
            "email" => $this->faker->email,
            'name' => $this->faker->firstName(),
            "password" => 'Pa$$w0rd!',
            "password_confirmation" => 'Pa$$w0rd!',
        ]);

        $response
            ->assertUnprocessable()
            ->assertInvalid('last_name')
            ->assertJsonValidationErrorFor('last_name')
            ->assertJsonValidationErrors(['last_name' => 'The last name field is required.']);
    }


    /** @test */
    public function user_cant_register_without_email()
    {
        $response = $this->postJson('api/register', [
            "name" => $this->faker->firstName,
            'last_name' => $this->faker->lastName(),
            "password" => 'Pa$$w0rd!',
            "password_confirmation" => 'Pa$$w0rd!',
        ]);

        $response
            ->assertUnprocessable()
            ->assertInvalid('email')
            ->assertJsonValidationErrorFor('email')
            ->assertJsonValidationErrors(['email' => 'The email field is required.']);
    }

    /** @test */
    public function user_cant_register_without_password()
    {
        $response = $this->postJson('api/register', [
            "name" => $this->faker->firstName,
            'last_name' => $this->faker->lastName(),
            "email" => $this->faker->email,
            "password_confirmation" => 'Pa$$w0rd!',
        ]);

        $response
            ->assertUnprocessable()
            ->assertInvalid('password')
            ->assertJsonValidationErrorFor('password')
            ->assertJsonValidationErrors(['password' => 'The password field is required.']);
    }

    /** @test */
    public function user_cant_register_without_password_confirmation()
    {
        $response = $this->postJson('api/register', [
            "name" => $this->faker->firstName,
            'last_name' => $this->faker->lastName(),
            "email" => $this->faker->email,
            "password" => 'Pa$$w0rd!',
        ]);

        $response
            ->assertUnprocessable()
            ->assertInvalid('password')
            ->assertJsonValidationErrorFor('password')
            ->assertJsonValidationErrors(['password' => 'The password confirmation does not match.']);
    }

    /** @test */
    public function user_cant_register_when_confirm_password_does_not_match_password()
    {
        $response = $this->postJson('api/register', [
            "name" => $this->faker->firstName,
            'last_name' => $this->faker->lastName(),
            "email" => $this->faker->email,
            "password" => 'Pa$$w0rd!',
            "password_confirmation" => 'Pa$$w0rd!123',
        ]);

        $response
            ->assertUnprocessable()
            ->assertInvalid('password')
            ->assertJsonValidationErrorFor('password')
            ->assertJsonValidationErrors(['password' => 'The password confirmation does not match.']);
    }

    /** @test */
    public function test_user_cant_register_if_email_exists()
    {
        $email = $this->faker->email;

        User::factory()->employee()->create(['email' => $email]);
        $this->assertDatabaseHas('users', ['email' => $email]);

        $response = $this->postJson('api/register', [
            "name" => $this->faker->firstName,
            'last_name' => $this->faker->lastName(),
            "email" => $email,
            "password" => 'Pa$$w0rd!',
            "password_confirmation" => 'Pa$$w0rd!',
        ]);

        $response
            ->assertUnprocessable()
            ->assertInvalid('email')
            ->assertJsonValidationErrorFor('email')
            ->assertJsonValidationErrors(['email' => 'The email has already been taken.']);
    }

}
