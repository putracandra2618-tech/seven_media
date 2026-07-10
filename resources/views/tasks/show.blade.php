@extends('layouts.app')

@section('title', $task->title)

@section('content')
    <div class="mb-4">
        <a href="{{ route('tasks.index') }}" class="text-decoration-none small">
            ← Kembali ke Daftar Task
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <h1 class="h2 fw-bold mb-0 {{ $task->is_done ? 'text-decoration-line-through text-muted' : '' }}">
                    {{ $task->title }}
                </h1>
                <span class="badge fs-6 {{ $task->is_done ? 'bg-success' : 'bg-warning text-dark' }}">
                    {{ $task->is_done ? 'Selesai' : 'Belum Selesai' }}
                </span>
            </div>

            @if($task->description)
                <div class="mb-4">
                    <h2 class="h6 text-muted fw-semibold">Deskripsi</h2>
                    <p class="mb-0">{{ $task->description }}</p>
                </div>
            @else
                <p class="text-muted fst-italic mb-4">Tidak ada deskripsi.</p>
            @endif

            <div class="row text-muted small mb-4">
                <div class="col-md-6">
                    <strong>Dibuat:</strong> {{ $task->created_at->format('d M Y, H:i') }}
                </div>
                <div class="col-md-6">
                    <strong>Diupdate:</strong> {{ $task->updated_at->format('d M Y, H:i') }}
                </div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('tasks.edit', $task) }}" class="btn btn-warning">
                    Edit Task
                </a>
                <form action="{{ route('tasks.destroy', $task) }}"
                      method="POST"
                      class="d-inline"
                      onsubmit="return confirm('Yakin hapus task ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
@endsection