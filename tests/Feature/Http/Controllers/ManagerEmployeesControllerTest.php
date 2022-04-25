<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ManagerEmployeesControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed --class RolesSeeder');
    }

    /** @test */
    public function manager_can_list_own_employees()
    {
        $employeesNumber = 2;

        // Create manager
        $manager = User::factory()->manager()->create();

        //  Create some employees
        $employees = User::factory($employeesNumber)->employee()->create();

        foreach ($employees as $employee) {

            if (!$manager->userIsEmployee($employee)) {

                $employee->managers()->attach($manager);
            }
        }

        $response = $this->actingAs($manager)
            ->getJson('api/user/employees?items=15');

        $response
            ->assertOk()
            ->assertJsonCount($employeesNumber, 'employees.data');
        $this->assertEquals('Employee', $response->json('employees.data')[0]['role']);
        $this->assertEquals('Employee', $response->json('employees.data')[1]['role']);
    }

    /** @test */
    public function manager_cant_list_own_employees_if_not_authenticated()
    {
        $response = $this
            ->getJson('api/user/employees');

        $response
            ->assertUnauthorized();
    }
}
