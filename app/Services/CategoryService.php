<?php

namespace App\Services;

use App\Models\Category;
use App\Models\User;

class CategoryService
{
    public function create(User $user, array $data): Category
    {
        return $user->categories()->create([
            'name' => $data['name'],
            'color' => $data['color'] ?? 'primary',
        ]);
    }

    public function update(Category $category, array $data): Category
    {
        $category->update([
            'name' => $data['name'],
            'color' => $data['color'] ?? $category->color,
        ]);

        return $category->fresh();
    }

    public function delete(Category $category): void
    {
        // Task yang pakai kategori: category_id jadi null (nullOnDelete)
        $category->delete();
    }
}
