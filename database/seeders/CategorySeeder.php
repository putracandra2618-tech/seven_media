<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'demo@taskmanager.test')->first();

        if (!$user) {
            return;
        }

        $categories = [
            ['name' => 'Work',     'color' => 'primary'],
            ['name' => 'Personal', 'color' => 'success'],
            ['name' => 'Study',    'color' => 'warning'],
        ];

        foreach ($categories as $category) {
            $user->categories()->create($category);
        }
    }
}
