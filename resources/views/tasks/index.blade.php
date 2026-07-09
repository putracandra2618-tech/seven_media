@extends('layouts.app')

@section('title', 'Daftar Task')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 fw-bold mb-1">Daftar Task</h1>
            <p class="text-muted mb-0 small">Data diambil dari database MySQL</p>
        </div>

        <span class="badge bg-primary fs-6">{{ $tasks->count() }} task</span>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h2 class="fw-bold text-primary">{{ $total }}</h2>
                    <p class="mb-0">Total</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h2 class="fw-bold text-success">{{ $done }}</h2>
                    <p class="mb-0">Selesai</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h2 class="fw-bold text-danger">{{ $pending }}</h2>
                    <p class="mb-0">Belum Selesai</p>
                </div>
            </div>
        </div>
    </div>
    
    @if($tasks->isEmpty())
        <div class="alert alert-info">
            Belum ada task di database.
            Jalankan <code>php artisan db:seed --class=TaskSeeder</code>
        </div>
    @else
        <div class="list-group shadow-sm">
            @foreach($tasks as $task)
                <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-start py-3">
                    <div class="me-3">
                        <h5 class="mb-1 fw-semibold {{ $task->is_done ? 'text-decoration-line-through text-muted' : '' }}">
                            {{ $task->title }}
                        </h5>
                        @if($task->description)
                            <p class="mb-1 small text-muted">{{ $task->description }}</p>
                        @endif
                        <small class="text-muted">
                            Dibuat: {{ $task->created_at->format('d M Y, H:i') }}
                        </small>
                    </div>
                    <span class="badge {{ $task->is_done ? 'bg-success' : 'bg-warning text-dark' }} align-self-center">
                        {{ $task->is_done ? 'Selesai' : 'Belum' }}
                    </span>
                </div>
            @endforeach
        </div>

        <div class="mt-3 d-flex justify-content-center">
            {{ $tasks->links() }}
        </div>
    @endif
@endsection