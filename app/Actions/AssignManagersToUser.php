<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class AssignManagersToUser
{
    use AsAction;

    public function handle(User $user)
    {
        // Check if user is employee
        if($user->role_id !== 1) // Employee
        {
            throw new \Exception("User is not Employee");
        }

        // Check if user has managers already
        $userManagersCount = $user->managers()->count();

        $userManagersToAssign = 2 - $userManagersCount;
        $managersWithNoUsers = [];

        // 1. Find the first two managers with the fewest employees
        // 2. First check for managers without employees
        if ($userManagersToAssign > 0) {

            $managersWithNoUsers = $this->getManagersWithoutEmployees($userManagersToAssign);

            foreach ($managersWithNoUsers as $manager) {

                if(!$manager->userIsEmployee($user)){
                    $manager->employees()->attach($user);
                }
            }
        }

        $userManagersToAssign -= count($managersWithNoUsers);

        // 3. Then check for managers with employees
        if ($userManagersToAssign > 0) {

            $managersWithEmployees = $this->getManagersWithFewestEmployees($userManagersToAssign);

            foreach ($managersWithEmployees as $managerId) {

                $manager = User::find($managerId);
                if(!$manager->userIsEmployee($user)){
                    $manager->employees()->attach($user);
                }
            }
        }

        return $user->managers;
    }

    private function getManagersWithFewestEmployees($number)
    {
        return DB::table('employee_managers')
            ->select('manager_id')
            ->groupBy('manager_id')
            ->orderByRaw('count(employee_id) ASC')
            ->limit($number)
            ->get()
            ->pluck('manager_id');
    }

    private function getManagersWithoutEmployees($number)
    {
        $managers = DB::table('employee_managers')
            ->select('manager_id')
            ->distinct()
            ->get()
            ->pluck('manager_id');

        return User::where('role_id', 2) // Manager
            ->whereNotIn('id', $managers)
            ->limit($number)
            ->get();
    }
}
