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
            <form action="{{ route('tasks.update', $task) }}" method="POST" enctype="multipart/form-data">
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

                <div class="mb-3">
                    <label for="category_id" class="form-label fw-semibold">Kategori</label>
                    <select name="category_id" id="category_id" class="form-select">
                        <option value="">— Tanpa Kategori —</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}"
                                {{ old('category_id', $task->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label d-block">Tags</label>

                    @forelse ($tags as $tag)
                        <div class="form-check form-check-inline">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="tags[]"
                                id="tag-{{ $tag->id }}"
                                value="{{ $tag->id }}"
                                @checked(collect(old('tags', $task->tags->pluck('id')->all()))->contains($tag->id))
                            >
                            <label class="form-check-label" for="tag-{{ $tag->id }}">
                                {{ $tag->name }}
                            </label>
                        </div>
                    @empty
                        <p class="text-muted mb-0">
                            Belum ada tag.
                            <a href="{{ route('tags.create') }}">Buat tag dulu</a>.
                        </p>
                    @endforelse

                    @error('tags')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                    @error('tags.*')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="due_date" class="form-label fw-semibold">Tanggal Akhir</label>
                    <input type="date"
                           name="due_date"
                           id="due_date"
                           value="{{  old('due_date', $task->due_date ? $task->due_date->format('Y-m-d') : '') }}"
                           class="form-control @error('due_date') is-invalid @enderror">
                    @error('due_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="attachments" class="form-label">Lampiran</label>

                    @if ($task->attachments->count() > 0)
                        <div class="mb-3">
                            <span class="text-muted d-block mb-2">Lampiran saat ini:</span>
                            <div class="list-group list-group-sm">
                                @foreach($task->attachments as $attachment)
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <a href="{{ route('tasks.attachment.download', [$task, $attachment]) }}" class="text-decoration-none">
                                                {{ $attachment->original_name }}
                                            </a>
                                            <small class="text-muted d-block">{{ $attachment->getExtension() }} • {{ $attachment->getFormattedSize() }}</small>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <input
                        type="file"
                        name="attachments[]"
                        id="attachments"
                        class="form-control @error('attachments.*') is-invalid @enderror"
                        accept=".jpg,.jpeg,.png,.gif,.webp,.pdf,image/*,application/pdf"
                        multiple
                    >
                    <div class="form-text">Kosongkan jika tidak ingin menambah. Maksimal 1 MB per file. Bisa pilih maksimal 6 files.</div>
                    @error('attachments.*')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <script>
                    document.getElementById('attachments').addEventListener('change', function(e) {
                        if (this.files.length > 6) {
                            alert('Maksimal 6 file yang bisa dipilih!');
                            this.value = '';
                        }
                    });
                </script>

                @if ($task->attachments->count() > 0)
                    <div class="mb-3 form-check">
                        <input class="form-check-input" type="checkbox" name="remove_attachments" value="1" id="remove_attachments">
                        <label class="form-check-label" for="remove_attachments">Hapus semua lampiran saat ini</label>
                    </div>
                @endif

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