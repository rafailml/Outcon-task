<?php

namespace Tests\Unit\Actions;

use App\Actions\AssignManagersToUser;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssignManagersToUserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed --class RolesSeeder');
    }

    /** @test */
    public function assign_manager_to_employee()
    {
        User::factory(2)->manager()->create();
        $user = User::factory()->employee()->create();

        AssignManagersToUser::run($user);

        $this->assertCount(2, $user->managers);
    }

    /** @test */
    public function cant_assign_manager_as_employee()
    {
        $manager = User::factory()->manager()->create();

        try {
            AssignManagersToUser::run($manager);
        } catch (\Exception $ex) {
            $this->assertSame('User is not Employee', $ex->getMessage());
        }
    }
}
