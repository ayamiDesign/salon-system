<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => '管理者',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_active' => 1,
            ]
        );

        User::updateOrCreate(
            ['email' => 'manager@example.com'],
            [
                'name' => '店長',
                'password' => Hash::make('password'),
                'role' => 'manager',
                'is_active' => 1,
            ]
        );

        User::updateOrCreate(
            ['email' => 'staff@example.com'],
            [
                'name' => 'スタッフ',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'is_active' => 1,
            ]
        );
    }
}