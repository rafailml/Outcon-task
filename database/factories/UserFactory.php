<?php

namespace Database\Factories;

use App\Actions\AssignManagersToUser;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (User $user) {
            //
        })->afterCreating(function (User $user) {

            // Assign managers to employee
            if ($user->role_id == 1) // Employee
            {
                AssignManagersToUser::run($user);
            }
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];
    }

    public function manager()
    {
        return $this->state(function (array $attributes) {
            return [
                'role_id' => 2, // Manager
            ];
        });
    }

    public function employee()
    {
        return $this->state(function (array $attributes) {
            return [
                'role_id' => 1, // Employee
            ];
        });
    }
}
