<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roleAdmin = new Role();
        $roleAdmin->name = 'Админинистратор';
        $roleAdmin->slug = 'admin';
        $roleAdmin->save();

        $roleModerator = new Role();
        $roleModerator->name = 'Модератор';
        $roleModerator->slug = 'moderator';
        $roleModerator->save();

        $roleUser = new Role();
        $roleUser->name = 'Пользователь';
        $roleUser->slug = 'user';
        $roleUser->save();
    }
}
