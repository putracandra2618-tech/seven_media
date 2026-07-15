@extends('layouts.app')

@section('title', 'Daftar Task')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 fw-bold mb-1">Daftar Task</h1>
            <p class="text-muted mb-0 small">Kelola semua task kamu</p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('tasks.export') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-file-csv"></i> Export CSV
            </a>

            <a href="{{ route('tasks.create') }}" class="btn btn-primary">
                + Tambah Task
            </a>
        </div>
    </div>

    {{-- Search & Filter --}}
    <form method="GET" action="{{ route('tasks.index') }}" class="card shadow-sm mb-4">
        <div class="card-body py-3">
            <div class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small fw-semibold mb-1">Cari Task</label>
                    <input type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Ketik judul task..."
                        class="form-control form-control-sm">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold mb-1">Kategori</label>
                    <select name="category" class="form-select form-select-sm">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}"
                                {{ request('category') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold mb-1">Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Semua Status</option>
                        <option value="done" {{ request('status') === 'done' ? 'selected' : '' }}>
                            Selesai
                        </option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>
                            Belum Selesai
                        </option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-1">
                    <button type="submit" class="btn btn-dark btn-sm flex-grow-1">Filter</button>
                    <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
                </div>
            </div>
        </div>
    </form>  

    @if($tasks->isEmpty())
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <p class="text-muted mb-3">Belum ada task.</p>
                <a href="{{ route('tasks.create') }}" class="btn btn-primary btn-sm">
                    Tambah Task Pertama
                </a>
            </div>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-striped table-hover bg-white shadow-sm">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Judul</th>
                        <th style="width: 120px;">Lampiran File</th>
                        <th style="width: 120px;">Tanggal Akhir</th>
                        <th style="width: 120px;">Status</th>
                        <th style="width: 180px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tasks as $index => $task)
                        <tr>
                            <td>{{ $tasks->firstItem() + $index }}</td>
                            <td>
                                <a href="{{ route('tasks.show', $task) }}"
                                class="text-decoration-none fw-semibold {{ $task->is_done ? 'text-muted text-decoration-line-through' : '' }}">
                                    {{ $task->title }}
                                </a>

                                @if($task->category)
                                    <span class="badge bg-{{ $task->category->color }} ms-1"
                                        style="font-size: 0.7rem;">
                                        {{ $task->category->name }}
                                    </span>
                                @endif

                                @if($task->description)
                                    <br>
                                    <small class="text-muted">
                                        {{ Str::limit($task->description, 60) }}
                                    </small>
                                @endif
                            </td>
                            <td>
                                @if ($task->attachment)
                                    <a href="{{ asset('storage/' . $task->attachment) }}"
                                       class="badge text-bg-secondary text-decoration-none"
                                       target="_blank"
                                       rel="noopener">
                                        File
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            <td>
                                @if($task->due_date)
                                    <span class="text-muted">
                                        {{ \Carbon\Carbon::parse($task->due_date)->format('d M Y') }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            <td>
                                <span class="badge {{ $task->is_done ? 'bg-success' : 'bg-warning text-dark' }}">
                                    {{ $task->is_done ? 'Selesai' : 'Belum' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('tasks.edit', $task) }}"
                                   class="btn btn-warning btn-sm">Edit</a>

                                <form action="{{ route('tasks.destroy', $task) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Yakin hapus task ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-3 d-flex justify-content-center">
            {{ $tasks->links('vendor.pagination.simple-bootstrap-5') }}
        </div>
    @endif
@endsection