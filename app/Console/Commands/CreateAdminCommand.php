<?php

namespace App\Console\Commands;


use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use App\Enums\RoleType;

class CreateAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:add {first_name} {last_name} {middle_name} {email} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $firstName = $this->argument('first_name');
        $lastName = $this->argument('last_name');
        $middleName = $this->argument('middle_name');
        $email = $this->argument('email');
        $password = $this->argument('password');

        $roleAdmin = Role::where('slug', 'admin')->first();
        $user = User::query()
            ->where('email', $email)
            ->first();

        if (!empty($user)) {
            $this->error('Email is used');
            return Command::FAILURE;
        }

        if(empty($roleAdmin)){
            $this->error('Role admin is not created');
            return Command::FAILURE;
        }

        $admin = new User();
        $admin->first_name = $firstName;
        $admin->last_name = $lastName;
        $admin->middle_name = $middleName;
        $admin->email = $email;
        $admin->password = Hash::make($password);
        $admin->save();
        $admin->fresh();

        $admin->roles()->attach([RoleType::Admin->value, RoleType::User->value]);
    }
}
