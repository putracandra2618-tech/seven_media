@extends('layouts.app')

@section('title', 'Edit Task')

@section('content')
    <div class="mb-4">
        <a href="{{ route('tasks.index') }}" class="text-decoration-none small">
            ← Kembali ke Daftar Task
        </a>
    </div>

    <h1 class="h2 fw-bold mb-4">Edit Task</h1>

    <div class="card shadow-sm">
        <div class="card-body p-4">
            <form action="{{ route('tasks.update', $task) }}" method="POST" novalidate>
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="title" class="form-label fw-semibold">
                        Judul Task <span class="text-danger">*</span>
                    </label>
                    <input type="text"
                           name="title"
                           id="title"
                           value="{{ old('title', $task->title) }}"
                           class="form-control @error('title') is-invalid @enderror"
                           autofocus>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label fw-semibold">Deskripsi</label>
                    <textarea name="description"
                              id="description"
                              rows="4"
                              class="form-control @error('description') is-invalid @enderror">{{ old('description', $task->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4 form-check">
                    <input type="checkbox"
                           name="is_done"
                           value="1"
                           id="is_done"
                           class="form-check-input"
                           {{ old('is_done', $task->is_done) ? 'checked' : '' }}>
                    <label for="is_done" class="form-check-label">
                        Tandai sudah selesai
                    </label>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        Update Task
                    </button>
                    <a href="{{ route('tasks.show', $task) }}" class="btn btn-outline-secondary">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection