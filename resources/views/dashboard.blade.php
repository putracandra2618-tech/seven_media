@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 fw-bold mb-1">Dashboard</h1>
            <p class="text-muted mb-0 small">Ringkasan task kamu, {{ auth()->user()->name }}</p>
        </div>
        <a href="{{ route('tasks.create') }}" class="btn btn-primary btn-sm">+ Task Baru</a>
    </div>

    {{-- Statistik Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card text-center shadow-sm border-0">
                <div class="card-body py-4">
                    <p class="display-5 fw-bold text-primary mb-1">{{ $total }}</p>
                    <p class="text-muted small mb-0">Total Task</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center shadow-sm border-0">
                <div class="card-body py-4">
                    <p class="display-5 fw-bold text-success mb-1">{{ $done }}</p>
                    <p class="text-muted small mb-0">Selesai</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center shadow-sm border-0">
                <div class="card-body py-4">
                    <p class="display-5 fw-bold text-warning mb-1">{{ $pending }}</p>
                    <p class="text-muted small mb-0">Belum Selesai</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Task Terbaru --}}
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white fw-semibold">
                    Task Terbaru
                </div>
                <div class="card-body p-0">
                    @forelse($recentTasks as $task)
                        <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                            <div>
                                <a href="{{ route('tasks.show', $task) }}"
                                   class="text-decoration-none fw-semibold small {{ $task->is_done ? 'text-muted text-decoration-line-through' : '' }}">
                                    {{ $task->title }}
                                </a>
                                @if($task->category)
                                    <span class="badge bg-{{ $task->category->color }} ms-1" style="font-size: 0.65rem;">
                                        {{ $task->category->name }}
                                    </span>
                                @endif
                            </div>
                            <span class="badge {{ $task->is_done ? 'bg-success' : 'bg-warning text-dark' }}" style="font-size: 0.65rem;">
                                {{ $task->is_done ? 'Selesai' : 'Belum' }}
                            </span>
                        </div>
                    @empty
                        <p class="text-muted small p-3 mb-0">Belum ada task.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Kategori --}}
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white fw-semibold d-flex justify-content-between align-items-center">
                    <span>Kategori</span>
                    <a href="{{ route('categories.index') }}" class="btn btn-outline-primary btn-sm">Kelola</a>
                </div>
                <div class="card-body p-0">
                    @forelse($categories as $cat)
                        <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                            <span>
                                <span class="badge bg-{{ $cat->color }}">{{ $cat->name }}</span>
                            </span>
                            <span class="text-muted small">{{ $cat->tasks_count }} task</span>
                        </div>
                    @empty
                        <p class="text-muted small p-3 mb-0">
                            Belum ada kategori.
                            <a href="{{ route('categories.create') }}">Buat sekarang</a>
                        </p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection