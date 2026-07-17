<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@taskmanager.test'],
            [
                'name'     => 'Administrator',
                'password' => Hash::make('password'),
                'role'     => 'admin',
            ]
        );

        // Pastikan ada minimal 1 user biasa untuk testing
        User::updateOrCreate(
            ['email' => 'user@taskmanager.test'],
            [
                'name'     => 'User Biasa',
                'password' => Hash::make('password'),
                'role'     => 'user',
            ]
        );
    }
}