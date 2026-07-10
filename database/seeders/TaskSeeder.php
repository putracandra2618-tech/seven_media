<?php

namespace Database\Seeders;
    

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TaskSeeder extends Seeder
    {
    public function run(): void
    {
        // Buat user demo jika belum ada
        $user = User::firstOrCreate(
            ['email' => 'demo@taskmanager.test'],
            [
                'name'     => 'Demo User',
                'password' => Hash::make('password123'),
            ]
        );  

        $tasks = [
            ['title' => 'Setup project Laravel', 'description' => 'Install & konfigurasi environment', 'is_done' => true],
            ['title' => 'Pelajari Routing & Controller', 'description' => 'Memahami alur MVC', 'is_done' => true],
            ['title' => 'Buat layout Blade', 'description' => 'Layout, navbar, partial', 'is_done' => true],
            ['title' => 'Hubungkan ke database', 'description' => 'Migration, model, seeder', 'is_done' => false],
            ['title' => 'Buat CRUD task', 'description' => 'Tambah, edit, hapus task', 'is_done' => false],
        ];

        foreach ($tasks as $task) {
            $user->tasks()->create($task);
        }       
    }
}