@extends('layouts.app')

@section('title', 'Kategori')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 fw-bold mb-0">Kategori</h1>
        <a href="{{ route('categories.create') }}" class="btn btn-primary btn-sm">+ Tambah Kategori</a>
    </div>

    @if($categories->isEmpty())
        <div class="alert alert-info">Belum ada kategori.</div>
    @else
        <div class="row g-3">
            @foreach($categories as $cat)
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <span class="badge bg-{{ $cat->color }} fs-6">{{ $cat->name }}</span>
                                <p class="text-muted small mb-0 mt-1">{{ $cat->tasks_count }} task</p>
                            </div>
                            <div class="d-flex gap-1">
                                <a href="{{ route('categories.edit', $cat) }}"
                                   class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('categories.destroy', $cat) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Hapus kategori? Task terkait akan kehilangan kategori.')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection