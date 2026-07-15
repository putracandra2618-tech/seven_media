<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


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
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_done'     => ['sometimes', 'boolean'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'due_date'    => ['nullable', 'date'],
            'attachments'  => [
                'nullable',
                'array',
                'max:6',
            ],
            'attachments.*'  => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,gif,webp,pdf',
                'max:1024',
                'dimensions:max_width=2000,max_height=2000',
            ],
        ], [
            'attachments.max' => 'Maksimal 6 file yang bisa diupload.',
            'attachments.*.max' => 'Ukuran lampiran maksimal 1 MB.',
            'attachments.*.mimes' => 'Lampiran harus berupa gambar atau PDF.',
            'attachments.*.dimensions' => 'Dimensi gambar maksimal 2000x2000 pixel.',
        ]);

        // Pastikan kategori milik user yang login (jika ada)
        if (!empty($validated['category_id'])) {
            $ownsCategory = auth()->user()
                ->categories()
                ->where('id', $validated['category_id'])
                ->exists();

            if (!$ownsCategory) {
                abort(403, 'Kategori tidak valid.');
            }
        }

        $validated['is_done'] = $request->boolean('is_done');
        $validated['user_id'] = auth()->id();

        // Hapus attachments dari validated, akan simpan manual setelah task dibuat
        unset($validated['attachments']);

        $task = Task::create($validated);

        // Simpan multiple attachments
        if ($request->hasFile('attachments')) {
            $dir = 'attachments/' . auth()->id();
            foreach ($request->file('attachments') as $file) {
                $path = $file->store($dir, 'public');
                $task->attachments()->create([
                    'path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                ]);
            }
        }

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task berhasil dibuat!');
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
        $this->authorizeTask($task); // dari Modul 07/08 — ownership check

        $validated = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_done'     => ['sometimes', 'boolean'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'due_date'    => ['nullable', 'date'],
            'attachments'  => [
                'nullable',
                'array',
                'max:6',
            ],
            'attachments.*'  => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,gif,webp,pdf',
                'max:1024',
                'dimensions:max_width=2000,max_height=2000',
            ],
        ], [
            'attachments.max' => 'Maksimal 6 file yang bisa diupload.',
            'attachments.*.max' => 'Ukuran lampiran maksimal 1 MB.',
            'attachments.*.mimes' => 'Lampiran harus berupa gambar atau PDF.',
            'attachments.*.dimensions' => 'Dimensi gambar maksimal 2000x2000 pixel.',
        ]);

        if (!empty($validated['category_id'])) {
            $ownsCategory = auth()->user()
                ->categories()
                ->where('id', $validated['category_id'])
                ->exists();

            if (!$ownsCategory) {
                abort(403, 'Kategori tidak valid.');
            }
        }

        $validated['is_done'] = $request->boolean('is_done');

        // Hapus semua lampiran lama jika checkbox remove_attachments dicentang
        if ($request->boolean('remove_attachments')) {
            foreach ($task->attachments as $attachment) {
                Storage::disk('public')->delete($attachment->path);
                $attachment->delete();
            }
        }

        // Hapus attachments dari validated, akan simpan manual
        unset($validated['attachments']);

        // Tambah lampiran baru
        if ($request->hasFile('attachments')) {
            $dir = 'attachments/' . auth()->id();
            foreach ($request->file('attachments') as $file) {
                $path = $file->store($dir, 'public');
                $task->attachments()->create([
                    'path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                ]);
            }
        }

        $task->update($validated);

        return redirect()
            ->route('tasks.show', $task)
            ->with('success', 'Task berhasil diupdate!');
    }

    public function destroy(Task $task)
    {
        $this->authorizeTask($task);

        // Hapus semua file attachments
        foreach ($task->attachments as $attachment) {
            Storage::disk('public')->delete($attachment->path);
            $attachment->delete();
        }

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

    public function pending(Task $task)
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

    public function attachmentUrl(): ?string
    {
        if (!$this->attachment) {
            return null;
        }

        return asset('storage/' . $this->attachment);
    }

    public function isImageAttachment(): bool
    {
        if (!$this->attachment) {
            return false;
        }

        $ext = strtolower(pathinfo($this->attachment, PATHINFO_EXTENSION));

        return in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true);
    }

    public function dueDate(): ?string
    {
        if (!$this->due_date) {
            return null;
        }

        return $this->due_date->format('Y-m-d');
    }

    public function isOverdue(): bool
    {
        if (!$this->due_date) {
            return false;
        }

        return $this->due_date->isBefore(now());
    }
}