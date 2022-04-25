<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class EmployeeManagersControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed --class RolesSeeder');

        User::factory(3)->manager()->create();
    }

    /** @test */
    public function employee_can_list_own_managers()
    {
        $user = User::factory()->employee()->create();

        $response = $this->actingAs($user)
            ->getJson('api/user/managers');

        $response
            ->assertOk()
            ->assertJsonCount(2, 'managers.data');
        $this->assertEquals('Manager', $response->json('managers.data')[0]['role']);
        $this->assertEquals('Manager', $response->json('managers.data')[1]['role']);
    }

    /** @test */
    public function employee_cant_list_own_managers_if_not_authenticated()
    {
        $response = $this
            ->getJson('api/user/managers');

        $response
            ->assertUnauthorized();
    }
}
