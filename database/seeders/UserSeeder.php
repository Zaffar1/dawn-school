<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $superAdminRole = Role::where('slug', 'super-admin')->first();
        $adminRole = Role::where('slug', 'admin')->first();
        $accountantRole = Role::where('slug', 'accountant')->first();
        $teacherRole = Role::where('slug', 'teacher')->first();

        User::updateOrCreate(
            ['email' => 'superadmin@superdawn.com'],
            [
                'name' => 'Super Admin User',
                'password' => Hash::make('password'),
                'role_id' => $superAdminRole->id,
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin@superdawn.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role_id' => $adminRole->id,
            ]
        );

        User::updateOrCreate(
            ['email' => 'accountant@superdawn.com'],
            [
                'name' => 'Accountant User',
                'password' => Hash::make('password'),
                'role_id' => $accountantRole->id,
            ]
        );

        User::updateOrCreate(
            ['email' => 'teacher@superdawn.com'],
            [
                'name' => 'Teacher User',
                'password' => Hash::make('password'),
                'role_id' => $teacherRole->id,
            ]
        );
    }
}
