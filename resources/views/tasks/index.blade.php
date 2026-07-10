@extends('layouts.app')

@section('title', 'Daftar Task')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 fw-bold mb-1">Daftar Task</h1>
            <p class="text-muted mb-0 small">Kelola semua task kamu</p>
        </div>
        <a href="{{ route('tasks.create') }}" class="btn btn-primary">
            + Tambah Task
        </a>
    </div>

    <form method="GET" class="mb-3">
    <input type="text" name="search" value="{{ request('search') }}"
           placeholder="Cari task..." class="form-control form-control-sm">
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
                                @if($task->description)
                                    <br>
                                    <small class="text-muted">
                                        {{ Str::limit($task->description, 60) }}
                                    </small>
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
            {{ $tasks->links() }}
        </div>
    @endif
@endsection