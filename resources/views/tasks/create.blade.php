@extends('layouts.app')

@section('title', 'Tambah Task')

@section('content')
    <div class="mb-4">
        <a href="{{ route('tasks.index') }}" class="text-decoration-none small">
            ← Kembali ke Daftar Task
        </a>
    </div>

    <h1 class="h2 fw-bold mb-4">Tambah Task Baru</h1>

    <div class="card shadow-sm">
        <div class="card-body p-4">
            <form action="{{ route('tasks.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Judul --}}
                <div class="mb-3">
                    <label for="title" class="form-label fw-semibold">
                        Judul Task <span class="text-danger">*</span>
                    </label>
                    <input type="text"
                           name="title"
                           id="title"
                           value="{{ old('title') }}"
                           class="form-control @error('title') is-invalid @enderror"
                           placeholder="Contoh: Selesaikan laporan mingguan"
                           autofocus>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Deskripsi --}}
                <div class="mb-3">
                    <label for="description" class="form-label fw-semibold">Deskripsi</label>
                    <textarea name="description"
                              id="description"
                              rows="4"
                              class="form-control @error('description') is-invalid @enderror"
                              placeholder="Detail task (opsional)">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Kategori --}}
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

                {{-- Tanggal Jatuh Tempo --}}
                <div class="mb-3">
                    <label for="due_date" class="form-label fw-semibold">Tanggal Jatuh Tempo</label>
                    <input type="date"
                           name="due_date"
                           id="due_date"
                           value="{{ old('due_date') }}"
                           class="form-control @error('due_date') is-invalid @enderror">
                    @error('due_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="attachments" class="form-label">Lampiran (opsional)</label>
                    <input
                        type="file"
                        name="attachments[]"
                        id="attachments"
                        class="form-control @error('attachments.*') is-invalid @enderror"
                        accept=".jpg,.jpeg,.png,.gif,.webp,.pdf,image/*,application/pdf"
                        multiple>
                    <div class="form-text">Gambar atau PDF, maksimal 1 MB per file. Bisa pilih maksimal 6 files.</div>
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

                {{-- Status selesai --}}
                <div class="mb-4 form-check">
                    <input type="checkbox"
                           name="is_done"
                           value="1"
                           id="is_done"
                           class="form-check-input"
                           {{ old('is_done') ? 'checked' : '' }}>
                    <label for="is_done" class="form-check-label">
                        Tandai sudah selesai
                    </label>
                </div>

                {{-- Tombol --}}
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        Simpan Task
                    </button>
                    <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection