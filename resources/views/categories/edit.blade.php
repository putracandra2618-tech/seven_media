@extends('layouts.app')

@section('title', 'Edit Kategori')

@section('content')
    <div class="mb-4">
        <a href="{{ route('categories.index') }}" class="text-decoration-none small">
            ← Kembali ke Daftar Kategori
        </a>
         
        <h1 class="h2 fw-bold">Edit Kategori</h1>

        <div class="card shadow-sm mt-3">
            <div class="card-body p-4">
                <form action="{{ route('categories.update', $category) }}" method="POST" novalidate>
                    @csrf
                    @method('PUT')

                    {{-- Nama --}}
                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">
                            Nama Kategori <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               name="name"
                               id="name"
                               value="{{ old('name', $category->name) }}"
                               class="form-control @error('name') is-invalid @enderror"
                               placeholder="Contoh: Pekerjaan, Pribadi, dll."
                               autofocus>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Warna --}}
                    <div class="mb-3">
                        <label for="color" class="form-label fw-semibold">
                            Warna Kategori <span class="text-danger">*</span>
                        </label>
                        <select name="color" class="form-select">
                            <option value="primary"   {{ old('color', $category->color ?? '') === 'primary'   ? 'selected' : '' }}>Biru (Primary)</option>
                            <option value="success"   {{ old('color', $category->color ?? '') === 'success'   ? 'selected' : '' }}>Hijau (Success)</option>
                            <option value="danger"    {{ old('color', $category->color ?? '') === 'danger'    ? 'selected' : '' }}>Merah (Danger)</option>
                            <option value="warning"   {{ old('color', $category->color ?? '') === 'warning'   ? 'selected' : '' }}>Kuning (Warning)</option>
                            <option value="info"      {{ old('color', $category->color ?? '') === 'info'      ? 'selected' : '' }}>Cyan (Info)</option>
                            <option value="secondary" {{ old('color', $category->color ?? '') === 'secondary' ? 'selected' : '' }}>Abu (Secondary)</option>
                        </select>
                        @error('color')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
@endsection


