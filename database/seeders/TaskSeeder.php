<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Task;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tasks = [
            [
                'title'       => 'Setup project Laravel',
                'description' => 'Install Laravel, konfigurasi .env, hubungkan MySQL',
                'is_done'     => true,
            ],
            [
                'title'       => 'Pelajari Routing & Controller',
                'description' => 'Memahami alur request → route → controller → view',
                'is_done'     => true,
            ],
            [
                'title'       => 'Buat layout Blade + Bootstrap CDN',
                'description' => 'Layout master, navbar, partial alert',
                'is_done'     => true,
            ],
            [
                'title'       => 'Hubungkan ke database MySQL',
                'description' => 'Migration, model Task, seeder, halaman list',
                'is_done'     => false,
            ],
            [
                'title'       => 'Buat CRUD task lengkap',
                'description' => 'Form tambah, edit, hapus dengan validasi',
                'is_done'     => false,
            ],
            [
                'title'       => 'Implementasi login & register',
                'description' => 'Auth manual tanpa Laravel UI',
                'is_done'     => false,
            ],
            [
                'title'       => 'Dashboard & kategori',
                'description' => 'Statistik task, filter, search',
                'is_done'     => false,
            ],
        ];

        foreach ($tasks as $task) {
            Task::create($task);
        }
    }
}
