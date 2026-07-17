<?php

namespace Database\Seeders;

    use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
    {
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            // password default dari factory adalah "password"
            ]);

        $this->call([
            AdminUserSeeder::class,
            TaskSeeder::class,
            CategorySeeder::class,
        ]);
    }
    }