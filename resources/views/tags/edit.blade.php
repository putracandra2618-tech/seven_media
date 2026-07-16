@extends('layouts.app')

@section('title', 'Edit Tag')

@section('content')
    <div class="mb-4">
        <a href="{{ route('tags.index') }}" class="text-decoration-none small">
            ← Kembali ke Daftar Tag
        </a>
    </div>

    <h1 class="h2 fw-bold">Edit Tag</h1>

    <div class="card shadow-sm mt-3">
        <div class="card-body p-4">
            <form action="{{ route('tags.update', $tag) }}" method="POST" novalidate>
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold">
                        Nama Tag <span class="text-danger">*</span>
                    </label>
                    <input type="text"
                           name="name"
                           id="name"
                           value="{{ old('name', $tag->name) }}"
                           class="form-control @error('name') is-invalid @enderror"
                           placeholder="Contoh: Urgent, Belanja, dll."
                           autofocus>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="color" class="form-label fw-semibold">
                        Warna Tag <span class="text-danger">*</span>
                    </label>
                    <select name="color" id="color" class="form-select @error('color') is-invalid @enderror">
                        <option value="primary" {{ old('color', $tag->color) === 'primary' ? 'selected' : '' }}>Biru</option>
                        <option value="success" {{ old('color', $tag->color) === 'success' ? 'selected' : '' }}>Hijau</option>
                        <option value="danger" {{ old('color', $tag->color) === 'danger' ? 'selected' : '' }}>Merah</option>
                        <option value="warning" {{ old('color', $tag->color) === 'warning' ? 'selected' : '' }}>Kuning</option>
                        <option value="info" {{ old('color', $tag->color) === 'info' ? 'selected' : '' }}>Cyan</option>
                        <option value="secondary" {{ old('color', $tag->color) === 'secondary' ? 'selected' : '' }}>Abu</option>
                    </select>
                    @error('color')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>
@endsection
