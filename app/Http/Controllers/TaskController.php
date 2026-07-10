<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = auth()->user()
            ->tasks()
            ->with('category')
            ->latest();

        // Search by judul
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Filter by kategori
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'done') {
                $query->where('is_done', true);
            } elseif ($request->status === 'pending') {
                $query->where('is_done', false);
            }
        }

        $tasks = $query->paginate(10)->withQueryString();
        $categories = auth()->user()->categories()->orderBy('name')->get();

        return view('tasks.index', compact('tasks', 'categories'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|min:3|max:255',
            'description' => 'nullable|string|max:1000',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $validated['is_done'] = $request->boolean('is_done');

        auth()->user()->tasks()->create($validated);

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task berhasil ditambahkan!');
    }

    public function show(Task $task)
    {
        $this->authorizeTask($task);

        return view('tasks.show', compact('task'));
    }

    public function create()
    {
        $categories = auth()->user()->categories()->orderBy('name')->get();

        return view('tasks.create', compact('categories'));
    }


        public function edit(Task $task)
    {
        $this->authorizeTask($task);

        $categories = auth()->user()->categories()->orderBy('name')->get();

        return view('tasks.edit', compact('task', 'categories'));
    }

    public function update(Request $request, Task $task)
    {
        $this->authorizeTask($task);

        $validated = $request->validate([
            'title'       => 'required|string|min:3|max:255',
            'description' => 'nullable|string|max:1000',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $validated['is_done'] = $request->boolean('is_done');

        $task->update($validated);

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task berhasil diupdate!');
    }

    public function destroy(Task $task)
    {
        $this->authorizeTask($task);

        $task->delete();

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task berhasil dihapus!');
    }

    /**
     * Pastikan task milik user yang sedang login.
     * Jika bukan → HTTP 403 Forbidden.
     */
    private function authorizeTask(Task $task): void
    {
        if ($task->user_id !== auth()->id()) {
            abort(403, 'Anda tidak berhak mengakses task ini.');
        }
    }
}