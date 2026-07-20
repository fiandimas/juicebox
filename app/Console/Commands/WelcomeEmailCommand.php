<?php

namespace App\Console\Commands;

use App\Jobs\SendWelcomeEmail;
use App\Services\UserService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:welcome-email {email}')]
#[Description('for sending welcome email dummy')]
class WelcomeEmailCommand extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(UserService $userService)
    {
        $email = $this->argument('email');
        $user = $userService->findByEmail($email);

        if (is_null($user)) {
            echo 'User is not found';
            return;
        }

        SendWelcomeEmail::dispatch($user);
    }
}
