<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserProfileControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed --class RolesSeeder');
    }

    /** @test */
    public function user_can_view_own_profile()
    {
        $user = User::factory()->employee()->create();

        $response = $this->actingAs($user)
            ->getJson('api/user/profile');

        $response
            ->assertJsonPath('name', $user->name)
            ->assertJsonPath('last_name', $user->last_name)
            ->assertJsonPath('email', $user->email)
            ->assertJsonPath('role', $user->roleName())
            ->assertOk();
    }

    /** @test */
    public function user_cant_view_own_profile_if_not_authorized()
    {
        $response = $this
            ->getJson('api/user/profile');

        $response
            ->assertUnauthorized();
    }

    /** @test */
    public function user_can_edit_own_profile()
    {
        $user = User::factory()->employee()->create();
        $newName = $this->faker->firstName();
        $newLastName = $this->faker->lastName();
        $newEmail = $this->faker->email();
        $newPassword = 'MyNewPa$$w0rd!';

        $response = $this->actingAs($user)
            ->putJson('api/user/profile',
                [
                    'name' => $newName,
                    'last_name' => $newLastName,
                    'email' => $newEmail,
                    'password' => $newPassword,
                    'password_confirmation' => $newPassword,
                ]);

        $response
            ->assertOK();

        $userEdited = User::where('email', $newEmail)->firstOrFail();
        $this->assertEquals($userEdited->name, $newName);
        $this->assertEquals($userEdited->last_name, $newLastName);
        $this->assertEquals($userEdited->email, $newEmail);
    }


    /** @test */
    public function user_cant_edit_own_profile_if_not_authorized()
    {
        $response = $this
            ->putJson('api/user/profile',
                [
                    'name' => $this->faker->firstName(),
                    'last_name' => $this->faker->lastName(),
                    'email' => $this->faker->email(),
                    'password' => 'MyNewPa$$w0rd!',
                    'password_confirmation' => 'MyNewPa$$w0rd!',
                ]);

        $response
            ->assertUnauthorized();
    }
}
