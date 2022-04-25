<?php

namespace App\Actions;

use App\Models\User;
use App\Notifications\EmailToCoworkers;
use Lorisleiva\Actions\Concerns\AsAction;

class SendEmailsToAllCoworkers
{
    use AsAction;

    public function handle(User $user)
    {
        // 1. find user managers
        foreach ($user->managers as $manager){

            // 2. get all managers employees and send emails
            foreach ($manager->employees as $employee) {

                if($employee->id !== $user->id)
                {
                    // Send email
                    $employee->notify(new EmailToCoworkers($user));
                }
            }
        }
    }
}
