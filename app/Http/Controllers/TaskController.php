<?php


namespace App\Http\Controllers;

use App\Http\Requests\UpdateTaskRequest;

use App\Http\Requests\StoreTaskRequest;
use App\Services\TaskService;
use App\Models\Task;
use App\Models\TaskAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;



class TaskController extends Controller
{

    public function __construct(
        protected \App\Services\Contracts\TaskServiceInterface $tasks
    ) {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = auth()->user()
            ->tasks()
            ->with(['category', 'tags', 'attachments'])
            ->withCount('tags');

        // Search by judul
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Filter by kategori
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter by tag
        if ($request->filled('tag')) {
            $tagId = (int) $request->tag;
            $query->whereHas('tags', function ($q) use ($tagId) {
                $q->where('tags.id', $tagId);
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'done') {
                $query->where('is_done', true);
            } elseif ($request->status === 'pending') {
                $query->where('is_done', false);
            }
        }

        $tasks = $query->latest()->paginate(10)->withQueryString();
        $categories = auth()->user()->categories()->orderBy('name')->get();
        $tags = auth()->user()->tags()->orderBy('name')->get();

        return view('tasks.index', compact('tasks', 'categories', 'tags'));
    }
    public function store(StoreTaskRequest $request)
    {
        $this->tasks->create(
            auth()->user(),
            $request->validated(),
            $request->file('attachments')
        );

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task berhasil ditambahkan!');
    }
    public function show(Task $task)
    {
        $this->authorize('view', $task);

        $task->load(['category', 'tags', 'user']);

        return view('tasks.show', compact('task'));
    }

    public function create()
    {
        $categories = auth()->user()->categories()->orderBy('name')->get();
        $tags = auth()->user()->tags()->orderBy('name')->get();

        return view('tasks.create', compact('categories', 'tags'));
    }

    public function edit(Task $task)
    {
        $this->authorize('update', $task);

        $task->load('attachments');

        $categories = auth()->user()->categories()->orderBy('name')->get();
        $tags = auth()->user()->tags()->orderBy('name')->get();

        // Admin mengedit task user lain: kategori/tag milik owner task
        if (auth()->user()->isAdmin() && $task->user_id !== auth()->id()) {
            $categories = $task->user->categories()->orderBy('name')->get();
            $tags = $task->user->tags()->orderBy('name')->get();
        }

        return view('tasks.edit', compact('task', 'categories', 'tags'));
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        // authorize sudah di Form Request; Policy tetap boleh dipanggil
        $this->authorize('update', $task);

        $this->tasks->update(
            $task,
            $request->validated(),
            $request->file('attachments')
        );

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task berhasil diupdate!');
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);

        $task->delete();

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task dipindahkan ke trash.');
    }

        public function trashed()
    {
            $query = auth()->user()->isAdmin()
                ? Task::onlyTrashed()->with(['user', 'category', 'tags'])
                : Task::onlyTrashed()->where('user_id', auth()->id())->with(['category', 'tags']);

            $tasks = $query->latest('deleted_at')->paginate(10);

            return view('tasks.trashed', compact('tasks'));
    }

    public function restoreAll()
    {
        $tasks = Task::onlyTrashed()
            ->where('user_id', auth()->id())
            ->get();

        foreach ($tasks as $task) {
            $task->restore();
        }

        return redirect()
            ->route('tasks.trashed')
            ->with('success', 'Semua task di trash berhasil dipulihkan!');
    }

    /**
     * Pulihkan task yang sudah di-soft delete
     */
    public function restore($id)
    {
        $task = Task::onlyTrashed()->findOrFail($id);
        $this->authorize('restore', $task);
        $task->restore();

        return back()->with('success', 'Task direstore.');
    }

    /**
     * Hapus task secara permanen dari database
     */
    public function forceDelete($id)
    {
        $task = Task::onlyTrashed()->findOrFail($id);
        $this->authorize('forceDelete', $task);

        // Hapus file attachments dan recordnya
        foreach ($task->attachments as $attachment) {
            Storage::disk('public')->delete($attachment->path);
            $attachment->delete();
        }

        // Detach tags
        $task->tags()->detach();

        $task->forceDelete();

        return back()->with('success', 'Task dihapus permanen.');
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

    public function pending()
    {
        $tasks = auth()->user()
            ->tasks()
            ->with('category')
            ->where('is_done', false)
            ->latest()
            ->paginate(5);

        return view('tasks.pending', compact('tasks'));
    }

    public function export()
    {
        $tasks = auth()->user()->tasks()->with('category')->get();

        $csv = "Judul,Deskripsi,Status,Kategori,Dibuat\n";
        foreach ($tasks as $task) {
            $csv .= implode(',', [
                '"' . $task->title . '"',
                '"' . ($task->description ?? '') . '"',
                $task->is_done ? 'Selesai' : 'Belum',
                $task->category?->name ?? '-',
                $task->created_at->format('Y-m-d'),
            ]) . "\n";
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="tasks.csv"');
    }

    /**
     * Download attachment file dengan ownership check
     */
    public function downloadAttachment(Task $task, TaskAttachment $attachment)
    {
        $this->authorizeTask($task);

        // Cek attachment milik task ini
        if ($attachment->task_id !== $task->id) {
            abort(403, 'Anda tidak berhak mengakses file ini.');
        }

        return Storage::disk('public')->download($attachment->path, $attachment->original_name);
    }

    public function destroyAttachment(Task $task, TaskAttachment $attachment)
    {
        $this->authorizeTask($task);

        // Cek attachment milik task ini
        if ($attachment->task_id !== $task->id) {
            abort(403, 'Anda tidak berhak menghapus file ini.');
        }

        $this->tasks->removeAttachment($task, $attachment);

        return back()->with('success', 'Lampiran berhasil dihapus!');
    }

    public function toggle(Task $task)
    {
        $this->authorizeTask($task);

        $this->tasks->toggle($task);

        return back()->with('success', 'Status task berhasil diubah!');
    }
}