<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = auth()->user()
            ->categories()
            ->withCount('tasks')
            ->orderBy('name')
            ->get();

        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:100',
            'color' => 'required|in:primary,success,danger,warning,info,secondary',
        ]);

        auth()->user()->categories()->create($validated);

        return redirect()
            ->route('categories.index')
            ->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function edit(Category $category)
    {
        $this->authorizeCategory($category);

        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $this->authorizeCategory($category);

        $validated = $request->validate([
            'name'  => 'required|string|max:100',
            'color' => 'required|in:primary,success,danger,warning,info,secondary',
        ]);

        $category->update($validated);

        return redirect()
            ->route('categories.index')
            ->with('success', 'Kategori berhasil diupdate!');
    }

    public function destroy(Category $category)
    {
        $this->authorizeCategory($category);

        $category->delete();

        return redirect()
            ->route('categories.index')
            ->with('success', 'Kategori berhasil dihapus!');
    }

    private function authorizeCategory(Category $category): void
    {
        if ($category->user_id !== auth()->id()) {
            abort(403, 'Anda tidak berhak mengakses kategori ini.');
        }
    }
}